<?php

namespace App\Http\Requests\V1;

use App\Enums\TransactionStatus;
use Illuminate\Validation\Rule;

class TransactionStoreRequest extends BaseRequest
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
            'invoice_no' => 'required|unique:transactions,invoice_no',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric:min:1',
            // 'status' => [Rule::enum(TransactionStatus::class)],
            'paid_at' => 'nullable|date',
        ];
    }
}
