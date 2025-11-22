<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\Geocoding\GeocodeServiceInterface;
use App\Services\Geocoding\GeocodingException;
use App\Models\Address;

class AddressController extends Controller
{
    use AuthorizesRequests;

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
        // 1. Validate input
        $data = $request->validate([
            'address'      => ['required', 'string', 'max:255'],
            'countryCode'  => ['nullable', 'string', 'max:2'],
            'languageCode' => ['nullable', 'string', 'max:5'],
        ]);

        try {
            // 2. Call the service
            $result = $geocoder->geocode($data['address'], [
                'country_code'  => $data['countryCode'] ?? null,
                'language_code' => $data['languageCode'] ?? 'en',
            ]);

            // 3. No result found
            if (! $result) {
                return back()->withErrors([
                    'address' => 'No matching location found for this address.',
                ]);
            }

            // 4. Map result to what your Vue form expects
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

            // 5. Flash data for Inertia (so you can read it in onSuccess)
            return back()->with('addressLookup', $mapped);

        } catch (GeocodingException $e) {
            // 6. API / network error
            return back()->withErrors([
                'address' => 'Address lookup failed: '.$e->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
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
        $this->authorize('view', $address);

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
}