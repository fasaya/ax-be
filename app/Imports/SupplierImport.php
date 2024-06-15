<?php

namespace App\Imports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SupplierImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Supplier([
            'code' => $row['code'],
            'name' => $row['name'],
            'address' => $row['address'],
            'phone' => $row['phone'],
            'email' => $row['email'],
            'npwp' => $row['npwp'],
            'pic_name' => $row['pic_name'],
            'pic_phone' => $row['pic_phone'],
            'pic_email' => $row['pic_email'],
            'preferred_payout' => $row['preferred_payout'],
        ]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function rules(): array
    {
        return [
            'code' => 'required|unique:suppliers,code',
            'name' => 'required',
            'address' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable|email',
            'npwp' => 'nullable',
            'pic_name' => 'nullable',
            'pic_phone' => 'nullable',
            'pic_email' => 'nullable|email',
            'preferred_payout' => 'nullable',
        ];
    }
}
