<?php

namespace App\Observers;

use App\Models\Address;
use App\Jobs\GeocodeAddressJob;

class AddressObserver
{
    public function created(Address $address): void
    {
        GeocodeAddressJob::dispatch($address);
    }

    public function updated(Address $address): void
    {
        if ($address->wasChanged(['line1', 'city', 'province', 'postal', 'country_code'])) {
            GeocodeAddressJob::dispatch($address);
        }
    }
}