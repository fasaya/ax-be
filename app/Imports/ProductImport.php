<?php

namespace App\Imports;

use App\Enums\ProductStatus;
use App\Models\Product;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $supplier = Supplier::where('code', $row['supplier'])->first();

        return new Product([
            'supplier_id' => $supplier->id,
            'code' => $row['code'],
            'name' => $row['name'],
            'description' => $row['description'],
            'status' => ProductStatus::ACTIVE,
            'price' => $row['price'],
            'discounted_price' => $row['discounted_price'],
            'stock' => $row['stock'],
            'image' => 'https://placehold.co/400x600',
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
            'supplier' => 'required|exists:suppliers,code',
            'code' => 'required|unique:products,code',
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:png|max:512',
        ];
    }
}
