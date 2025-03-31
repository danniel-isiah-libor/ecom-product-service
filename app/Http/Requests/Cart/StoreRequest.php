<?php

namespace App\Http\Requests\Cart;

use App\Rules\QuantityRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'user_id' => [
                'required',
                'integer'
            ],
            'product_id' => [
                'required',
                'integer',
                'exists:products,id'
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
                (new QuantityRule())
            ]
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => request()->user()->id
        ]);
    }
}
