<?php

namespace App\Http\Requests\V1;

use App\Enums\ProductStatus;
use Illuminate\Validation\Rule;


class ProductStoreRequest extends BaseRequest
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
            'supplier_id' => 'required|exists:suppliers,id',
            'code' => 'required|unique:products,code',
            'name' => 'required',
            'description' => 'nullable',
            'status' => ['nullable', Rule::enum(ProductStatus::class)],
            'price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:png|max:512',
        ];
    }
}
