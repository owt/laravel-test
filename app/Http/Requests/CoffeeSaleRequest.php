<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CoffeeSaleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'quantity' => ['required', 'integer', 'min:1'],
            'unit_cost' => ['required', 'numeric', 'min:0.01'],
        ];
    }
}
