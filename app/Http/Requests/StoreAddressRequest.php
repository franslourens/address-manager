<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'address'       => ['required', 'string', 'max:255'],
            'city'          => ['nullable', 'string', 'max:255'],
            'state'         => ['nullable', 'string', 'max:255'],
            'postalCode'    => ['nullable', 'numeric'],
            'countryCode'   => ['nullable', 'string', 'size:2'],
            'latitude'      => ['nullable', 'numeric'],
            'longitude'     => ['nullable', 'numeric'],
            'locationType'  => ['nullable', 'string', 'max:255'],
        ];
    }
}
