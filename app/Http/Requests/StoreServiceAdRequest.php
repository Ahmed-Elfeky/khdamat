<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceAdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'price_type' => 'nullable|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'service_type_id' => 'nullable|exists:service_types,id',
            'city_id' => 'nullable|exists:cities,id',
            'region_id' => 'nullable|exists:regions,id',
            'user_id' => 'required|exists:users,id',
        ];
    }
}
