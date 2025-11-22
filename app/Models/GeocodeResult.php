<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeocodeResult extends Model
{
   protected $fillable = [
        'address_id',
        'latitude',
        'longitude',
        'canonical_address',
        'country',
        'city',
        'postal_code',
        'location_type',
        'raw',
    ];

    protected $casts = [
        'raw' => 'object',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
