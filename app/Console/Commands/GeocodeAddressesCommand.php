<?php

namespace App\Console\Commands;

use App\Jobs\GeocodeAddressJob;
use App\Models\Address;
use Illuminate\Console\Command;

class GeocodeAddressesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Example:
     *  php artisan addresses:geocode --status=pending
     */
    protected $signature = 'addresses:geocode
        {--status=pending : pending, failed, or both}
        {--limit=100 : Maximum number of addresses to queue}';

    /**
     * The console command description.
     */
    protected $description = 'Dispatch geocoding jobs for addresses by status.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $statusOption = strtolower($this->option('status'));
        $limit        = (int) $this->option('limit');

        if (! in_array($statusOption, ['pending', 'failed', 'both'], true)) {
            $this->error('Invalid --status value. Use pending, failed, or both.');
            return self::FAILURE;
        }

        $this->info(sprintf(
            'Finding addresses with status: %s (limit %d)',
            $statusOption,
            $limit
        ));

        $query = Address::query();

        if ($statusOption === 'pending') {
            $query->where('status', Address::STATUS_PENDING);
        } elseif ($statusOption === 'failed') {
            $query->where('status', Address::STATUS_FAILED);
        } else { // both
            $query->whereIn('status', [
                Address::STATUS_PENDING,
                Address::STATUS_FAILED,
            ]);
        }

        $addresses = $query
            ->orderBy('id')
            ->limit($limit)
            ->get();

        if ($addresses->isEmpty()) {
            $this->info('No matching addresses found.');
            return self::SUCCESS;
        }

        $this->info(sprintf('Dispatching jobs for %d addresses…', $addresses->count()));

        $bar = $this->output->createProgressBar($addresses->count());
        $bar->start();

        foreach ($addresses as $address) {
            GeocodeAddressJob::dispatch($address);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('All geocode jobs dispatched.');

        return self::SUCCESS;
    }
}