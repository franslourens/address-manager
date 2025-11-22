<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use App\Services\Geocoding\GeocodeServiceInterface;
use App\Services\Geocoding\GeocodingException;
use App\Models\Address;

class AddressController extends Controller
{
    public function create()
    {
        return inertia('Address/Create');
    }

    public function index()
    {
        return inertia(
            'Address/Index',
            [
                'addresses' => Address::mostRecent()
                    ->paginate(5)
                    ->withQueryString()
            ]
        );
    }

    public function lookup(Request $request, GeocodeServiceInterface $geocoder)
    {
        $data = $request->validate([
            'address'      => ['required', 'string', 'max:255'],
            'countryCode'  => ['nullable', 'string', 'max:2'],
            'languageCode' => ['nullable', 'string', 'max:5'],
        ]);

        try {
            $result = $geocoder->geocode($data['address'], [
                'country_code'  => $data['countryCode'] ?? null,
                'language_code' => $data['languageCode'] ?? 'en',
            ]);

            if (! $result) {
                return back()->withErrors([
                    'address' => 'No matching location found for this address.',
                ]);
            }

            $mapped = [
                'address'       => $result->formattedAddress,
                'latitude'      => $result->latitude,
                'longitude'     => $result->longitude,
                'postalAddress' => $result->postalCode,
                'city'          => $result->city,
                'state'         => $result->state,
                'countryCode'   => $result->countryCode,
                'locationType'  => $result->locationType,
                'buildingType'  => $result->buildingType,
            ];

            return back()->with('addressLookup', $mapped);

        } catch (GeocodingException $e) {
            return back()->withErrors([
                'address' => 'Address lookup failed: '.$e->getMessage(),
            ]);
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'address'       => ['required', 'string', 'max:255'],
            'city'          => ['nullable', 'string', 'max:255'],
            'state'         => ['nullable', 'string', 'max:255'],
            'postalAddress' => ['nullable', 'string', 'max:20'],
            'countryCode'   => ['nullable', 'string', 'size:2'],
            'latitude'      => ['nullable', 'numeric'],
            'longitude'     => ['nullable', 'numeric'],
            'locationType'  => ['nullable', 'string', 'max:255'],
        ]);

        $request->user()->addresses()->create([
            'line1'         => $data['address'],
            'line2'         => null,
            'city'          => $data['city'] ?? null,
            'province'      => $data['state'] ?? null,
            'postal'        => $data['postalAddress'] ?? null,
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

    public function update(Request $request, Address $address)
    {
        $data = $request->validate([
            'line1'         => ['required', 'string', 'max:255'],
            'line2'         => ['nullable', 'string', 'max:255'],
            'city'          => ['nullable', 'string', 'max:255'],
            'province'      => ['nullable', 'string', 'max:255'],
            'postal'        => ['nullable', 'string', 'max:20'],
            'country_code'  => ['nullable', 'string', 'size:2'],
        ]);

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