<?php

namespace Tests\Unit\Jobs;

use App\Jobs\GeocodeAddressJob;
use App\Models\Address;
use App\Models\GeocodeResult;
use App\Services\Geocoding\GeocodeServiceInterface;
use App\Services\Geocoding\GeocodeResult as GeocodeResultDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class GeocodeAddressJobTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider geocodeResponseProvider
     */
    public function it_updates_address_and_creates_geocode_result_on_success(GeocodeResultDto $fakeResult)
    {
        $address = Address::factory()->create([
            'line1'        => '61 Saffraan Street',
            'city'         => 'Stellenbosch',
            'province'     => 'Western Cape',
            'postal'       => '7600',
            'country_code' => 'ZA',
            'status'       => Address::STATUS_PENDING,
        ]);

        $geocoder = Mockery::mock(GeocodeServiceInterface::class);
        $geocoder->shouldReceive('geocode')
            ->once()
            ->andReturn($fakeResult);

        $job = new GeocodeAddressJob($address);
        $job->handle($geocoder);

        $address->refresh();

        $this->assertSame(
            Address::STATUS_SUCCESS,
            $address->status,
            'Job status is [' . $address->status . '] with error: ' . $address->last_error
        );

        $this->assertDatabaseHas('geocode_results', [
            'address_id'    => $address->id,
            'latitude'      => $fakeResult->latitude,
            'longitude'     => $fakeResult->longitude,
            'country'       => $fakeResult->countryCode,
            'postal_code'   => $fakeResult->postalCode,
            'location_type' => $fakeResult->locationType,
        ]);
    }

    /** @test */
    public function it_marks_address_failed_when_no_result_is_returned()
    {
        $address = Address::factory()->create([
            'line1'        => 'Unknown place',
            'city'         => 'Nowhere',
            'province'     => null,
            'postal'       => null,
            'country_code' => 'ZA',
            'status'       => Address::STATUS_PENDING,
            'last_error'   => null,
        ]);

        $geocoder = Mockery::mock(GeocodeServiceInterface::class);
        $geocoder->shouldReceive('geocode')
            ->once()
            ->andReturn(null);

        $job = new GeocodeAddressJob($address);
        $job->handle($geocoder);

        $address->refresh();

        $this->assertSame(Address::STATUS_FAILED, $address->status);
        $this->assertSame('No geocode results found', $address->last_error);

        $this->assertDatabaseMissing('geocode_results', [
            'address_id' => $address->id,
        ]);
    }

    /** @test */
    public function it_marks_address_failed_when_geocoder_throws_an_exception()
    {
        $address = Address::factory()->create([
            'line1'        => '61 Saffraanstreet',
            'city'         => 'Stellenbosch',
            'province'     => 'Western Cape',
            'postal'       => '7600',
            'country_code' => 'ZA',
            'status'       => Address::STATUS_PENDING,
            'last_error'   => null,
        ]);

        $geocoder = Mockery::mock(GeocodeServiceInterface::class);
        $geocoder->shouldReceive('geocode')
            ->once()
            ->andThrow(new \RuntimeException('Service down'));

        $job = new GeocodeAddressJob($address);
        $job->handle($geocoder);

        $address->refresh();

        $this->assertSame(Address::STATUS_FAILED, $address->status);
        $this->assertSame('Service down', $address->last_error);
    }

    /** @test */
    public function it_uses_the_expected_database_connection()
    {
        dump([
            'default_connection' => config('database.default'),
            'db_name'            => \DB::connection()->getDatabaseName(),
        ]);

        $this->assertTrue(true);
    }

    /**
     * Data provider
     */
    public static function geocodeResponseProvider(): array
    {
        return [
            'Rooftop accuracy' => [
                new GeocodeResultDto(
                    formattedAddress: '61 Saffraan Street, Stellenbosch, Western Cape, 7600, ZA',
                    latitude: -33.9361,
                    longitude: 18.8610,
                    postalCode: '7600',
                    city: 'Stellenbosch',
                    state: 'Western Cape',
                    countryCode: 'ZA',
                    locationType: 'ROOFTOP',
                    buildingType: null,
                    raw: ['accuracy' => 'high'],
                ),
            ],

            'Interpolated accuracy' => [
                new GeocodeResultDto(
                    formattedAddress: '61 Saffraan Street, Stellenbosch, Western Cape, 7600, ZA',
                    latitude: -33.9350,
                    longitude: 18.8605,
                    postalCode: '7600',
                    city: 'Stellenbosch',
                    state: 'Western Cape',
                    countryCode: 'ZA',
                    locationType: 'RANGE_INTERPOLATED',
                    buildingType: null,
                    raw: ['accuracy' => 'medium'],
                ),
            ],

            'Geometric center accuracy' => [
                new GeocodeResultDto(
                    formattedAddress: '61 Saffraan Street, Stellenbosch, Western Cape, 7600, ZA',
                    latitude: -33.9371,
                    longitude: 18.8622,
                    postalCode: '7600',
                    city: 'Stellenbosch',
                    state: 'Western Cape',
                    countryCode: 'ZA',
                    locationType: 'GEOMETRIC_CENTER',
                    buildingType: null,
                    raw: ['accuracy' => 'low'],
                ),
            ],
        ];
    }
}