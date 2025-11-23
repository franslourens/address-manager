<?php

namespace App\Repositories;

use App\Models\Address;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AddressRepository
{
    public function collection(?int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return Address::mostRecent()
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn (Address $address) => [
                'id'            => $address->id,
                'line1'         => $address->line1,
                'status'        => $address->status,
                'last_error'    => $address->last_error,
                'deleted_at'    => $address->deleted_at,
                'belongs_to_user' => $userId !== null && $userId === $address->by_user_id,
            ]);
    }
}