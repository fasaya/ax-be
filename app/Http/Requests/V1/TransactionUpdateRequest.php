<?php

namespace App\Http\Requests\V1;

use App\Enums\TransactionStatus;
use Illuminate\Validation\Rule;

class TransactionUpdateRequest extends BaseRequest
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
            'invoice_no' => 'required|unique:transactions,invoice_no,' . request()->transaction,
            // 'product_id' => 'required|exists:products,id',
            // 'quantity' => 'required|numeric|min:1',
            // 'status' => ['nullable', Rule::enum(TransactionStatus::class)],
            'item_price' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'paid_at' => 'required|date',
        ];
    }
}
