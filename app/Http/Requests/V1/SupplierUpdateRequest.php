<?php

namespace App\Http\Requests\V1;


class SupplierUpdateRequest extends BaseRequest
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
            'code' => 'required|unique:suppliers,code,' . request()->supplier,
            'name' => 'required',
            'address' => 'nullable',
            'phone' => 'nullable|regex:/^[\d\s\+]*$/',
            'email' => 'nullable|email',
            'npwp' => 'nullable',
            'pic_name' => 'nullable',
            'pic_phone' => 'nullable|regex:/^[\d\s\+]*$/',
            'pic_email' => 'nullable',
            'preferred_payout' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'phone.regex' => 'Phone format is incorrect',
            'pic_phone.regex' => 'PIC phone format is incorrect',
        ];
    }
}
