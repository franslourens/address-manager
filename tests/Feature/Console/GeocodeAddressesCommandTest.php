<?php

namespace Tests\Feature\Console;

use App\Jobs\GeocodeAddressJob;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class GeocodeAddressesCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_dispatches_jobs_for_pending_addresses_by_default()
    {
        Queue::fake();

        $pending1 = Address::factory()->create(['status' => Address::STATUS_PENDING]);
        $pending2 = Address::factory()->create(['status' => Address::STATUS_PENDING]);
        $failed   = Address::factory()->create(['status' => Address::STATUS_FAILED]);

        // Reset queue recordings so we only track jobs from the command
        Queue::fake();

        $this->artisan('addresses:geocode')
            ->assertExitCode(0);

        Queue::assertPushed(GeocodeAddressJob::class, 2);

        Queue::assertPushed(GeocodeAddressJob::class, function ($job) use ($pending1, $pending2) {
            return $job->address->is($pending1) || $job->address->is($pending2);
        });

        Queue::assertNotPushed(GeocodeAddressJob::class, function ($job) use ($failed) {
            return $job->address->is($failed);
        });
    }

    /** @test */
    public function it_dispatches_jobs_for_pending_status()
    {
        Queue::fake();

        $pending = Address::factory()->count(3)->create([
            'status' => Address::STATUS_PENDING,
        ]);

        $failed = Address::factory()->create([
            'status' => Address::STATUS_FAILED,
        ]);

        Queue::fake(); // reset

        $this->artisan('addresses:geocode', ['--status' => 'pending'])
            ->assertExitCode(0);

        Queue::assertPushed(GeocodeAddressJob::class, 3);

        Queue::assertNotPushed(GeocodeAddressJob::class, function ($job) use ($failed) {
            return $job->address->is($failed);
        });
    }

    /** @test */
    public function it_dispatches_jobs_for_failed_status()
    {
        Queue::fake();

        $pending = Address::factory()->create([
            'status' => Address::STATUS_PENDING,
        ]);

        $failed1 = Address::factory()->create([
            'status' => Address::STATUS_FAILED,
        ]);
        $failed2 = Address::factory()->create([
            'status' => Address::STATUS_FAILED,
        ]);

        Queue::fake(); // reset

        $this->artisan('addresses:geocode', ['--status' => 'failed'])
            ->assertExitCode(0);

        Queue::assertPushed(GeocodeAddressJob::class, 2);

        Queue::assertPushed(GeocodeAddressJob::class, function ($job) use ($failed1, $failed2) {
            return $job->address->is($failed1) || $job->address->is($failed2);
        });

        Queue::assertNotPushed(GeocodeAddressJob::class, function ($job) use ($pending) {
            return $job->address->is($pending);
        });
    }

    /** @test */
    public function it_dispatches_jobs_for_both_pending_and_failed_statuses()
    {
        Queue::fake();

        $pending = Address::factory()->count(2)->create([
            'status' => Address::STATUS_PENDING,
        ]);

        $failed = Address::factory()->count(2)->create([
            'status' => Address::STATUS_FAILED,
        ]);

        Queue::fake(); // reset

        $this->artisan('addresses:geocode', ['--status' => 'both'])
            ->assertExitCode(0);

        Queue::assertPushed(GeocodeAddressJob::class, 4);

        foreach ($pending as $addr) {
            Queue::assertPushed(GeocodeAddressJob::class, function ($job) use ($addr) {
                return $job->address->is($addr);
            });
        }

        foreach ($failed as $addr) {
            Queue::assertPushed(GeocodeAddressJob::class, function ($job) use ($addr) {
                return $job->address->is($addr);
            });
        }
    }

    /** @test */
    public function it_respects_the_limit_option()
    {
        Queue::fake();

        Address::factory()->count(5)->create([
            'status' => Address::STATUS_PENDING,
        ]);

        Queue::fake(); // reset

        $this->artisan('addresses:geocode', [
            '--status' => 'pending',
            '--limit'  => 2,
        ])->assertExitCode(0);

        Queue::assertPushed(GeocodeAddressJob::class, 2);
    }

    /** @test */
    public function it_fails_on_invalid_status_and_does_not_dispatch_any_jobs()
    {
        Queue::fake();

        Address::factory()->count(3)->create([
            'status' => Address::STATUS_PENDING,
        ]);

        Queue::fake(); // reset

        $this->artisan('addresses:geocode', ['--status' => 'invalid'])
            ->expectsOutput('Invalid --status value. Use pending, failed, or both.')
            ->assertExitCode(1);

        Queue::assertNothingPushed();
    }

    /** @test */
    public function it_outputs_message_when_no_matching_addresses_found()
    {
        Queue::fake();

        // no addresses created

        $this->artisan('addresses:geocode', ['--status' => 'pending'])
            ->expectsOutput('No matching addresses found.')
            ->assertExitCode(0);

        Queue::assertNothingPushed();
    }
}