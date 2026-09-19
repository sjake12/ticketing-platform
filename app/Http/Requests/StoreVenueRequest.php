<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVenueRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'layout' => ['required', 'array'],
            'layout.sections' => ['required', 'array', 'min:1'],
            'layout.sections.*.name' => ['required', 'string', 'max:10'],
            'layout.sections.*.rows' => ['required', 'integer', 'min:1', 'max:50'],
            'layout.sections.*.seats_per_row' => ['required', 'integer', 'min:1', 'max:50'],
        ];
    }
}
