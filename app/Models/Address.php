<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['latitude', 'longitude'];

    protected $fillable = [
        'line1',
        'line2',
        'city',
        'province',
        'postal',
        'country_code',
        'status',
        'last_error',
    ];

    public const STATUS_PENDING    = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SUCCESS    = 'success';
    public const STATUS_FAILED     = 'failed';

    public function owner(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'by_user_id'
        );
    }

    public function geocodeResults()
    {
        return $this->hasMany(GeocodeResult::class);
    }

    public function latestGeocodeResult()
    {
        return $this->hasOne(GeocodeResult::class)->latestOfMany();
    }

    public function scopeMostRecent(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }

    public function getLatitudeAttribute()
    {
        return $this->latestGeocodeResult?->latitude;
    }

    public function getLongitudeAttribute()
    {
        return $this->latestGeocodeResult?->longitude;
    }

    public function formatted() 
    {
        return $this->line1 . " ". $this->city;
    }
}
