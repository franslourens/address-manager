<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Address;
use App\Repositories\AddressRepository;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Jobs\GeocodeAddressJob;

class AddressController extends Controller
{
    public function __construct(
        protected AddressRepository $addresses,
    ) {}

    public function create()
    {
        return inertia('Address/Create');
    }

    public function index(Request $request)
    {
        $userId = $request->user()?->id;

        return inertia(
            'Address/Index',
            [
                'addresses' => $this->addresses->collection($userId),
            ]
        );
    }

    public function retryGeocode(Address $address)
    {
        Gate::authorize(
            'update',
            $address
        );

        $address->update([
            'status'     => Address::STATUS_PENDING,
            'last_error' => null,
        ]);

        GeocodeAddressJob::dispatch($address);

        return back()->with('success', 'Geocoding has been re-queued for this address.');
    }

    public function store(StoreAddressRequest $request)
    {
        $data = $request->validated();

        $request->user()->addresses()->create([
            'line1'         => $data['address'],
            'line2'         => null,
            'city'          => $data['city'] ?? null,
            'province'      => $data['state'] ?? null,
            'postal'        => $data['postalCode'] ?? null,
            'country_code'  => $data['countryCode'] ?? null,
            'status'        => Address::STATUS_PENDING,
            'last_error'    => null,
        ]);

        return redirect()
            ->route('address.index')
            ->with('success', 'Address was created!');
    }

    public function show(Address $address)
    {
        return inertia('Address/Show', ['address' => $address]);
    }

    public function edit(Address $address)
    {
        Gate::authorize(
            'update',
            $address
        );

        return inertia(
            'Address/Edit',
            [
                'address' => $address
            ]
        );
    }

    public function update(UpdateAddressRequest $request, Address $address)
    {
        $data = $request->validated();

        $address->update([
            ...$data,
            'status'     => Address::STATUS_PENDING,
            'last_error' => null,
        ]);

        return redirect()
            ->route('address.index')
            ->with('success', 'Address updated. Geocoding in progress...');
    }

    public function destroy(Address $address)
    {
        Gate::authorize(
            'delete',
            $address
        );

        $address->deleteOrFail();

        return redirect()->back()
            ->with('success', 'Address was deleted!');
    }

    public function restore(Address $address)
    {
        Gate::authorize(
            'restore',
            $address
        );

        $address->restore();

        return redirect()->back()->with('success', 'Address was restored!');
    }

}