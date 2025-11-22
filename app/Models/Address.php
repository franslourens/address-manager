<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function geocode()
    {
        return $this->hasOne(GeocodeResult::class);
    }

    public function scopeMostRecent(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }
}
