<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Stock;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Imports\ProductImport;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Http\Requests\V1\ProductImportRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\V1\ProductResource;
use App\Http\Resources\V1\ProductCollection;
use App\Http\Requests\V1\ProductStoreRequest;
use App\Http\Requests\V1\ProductUpdateRequest;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Product::query();

        if ($request->search) {
            $data = $data->whereAny([
                'code',
                'name',
                'description',
            ], 'LIKE', '%' . $request->search . '%');

            $data = $data->orWhereHas('supplier', function ($query) use ($request) {
                $query->where('code', 'LIKE', '%' . $request->search . '%');
                $query->orWhere('name', 'LIKE', '%' . $request->search . '%');
            });
        }

        $data = $data->orderBy('id', 'DESC');

        $data = $request->per_page ? $data->paginate($request->per_page) : $data->get();

        return new ProductCollection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        $data = $request->only([
            'supplier_id',
            'code',
            'name',
            'description',
            'status',
            'price',
            'discounted_price',
            'stock',
        ]);

        $data['image'] = 'https://placehold.co/400x600';

        try {
            $product = \DB::transaction(function () use ($request, $data) {
                $product = Product::create($data);

                if ($product->stock > 0) {
                    $stock = Stock::create([
                        'product_id' => $product->id,
                        'quantity' => $request->stock
                    ]);
                }

                return $product;
            });
        } catch (\Exception $e) {
            return ResponseHelper::sendErrorResponse($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return ProductResource::make($product);
    }

    /**
     * Display the specified resource.
     */
    public function show($product)
    {
        $product = Product::find($product);

        if (!$product) {
            return ResponseHelper::sendErrorNotFoundResponse();
        }

        return ProductResource::make($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, $product)
    {
        $product = Product::find($product);

        if (!$product) {
            return ResponseHelper::sendErrorNotFoundResponse();
        }

        $data = $request->only([
            'supplier_id',
            'code',
            'name',
            'description',
            'status',
            'price',
            'discounted_price',
        ]);

        $product->update($data);

        $product = Product::find($product->id);

        return ProductResource::make($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product)
    {
        $product = Product::find($product);

        if (!$product) {
            return ResponseHelper::sendErrorNotFoundResponse();
        }

        try {
            \DB::transaction(function () use ($product) {
                $stocks = Stock::where('product_id', $product->id)->delete();
                $product->delete();
            });
        } catch (\Throwable $th) {
            return ResponseHelper::sendErrorResponse("Failed to delete", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return ResponseHelper::sendResponse(null, 'Data successfully deleted');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function import(ProductImportRequest $request)
    {
        Excel::import(new ProductImport, $request->file('file'));

        return ResponseHelper::sendResponse(null, 'Data successfully imported');
    }

    public function downloadImportTemplate(Request $request)
    {
        $filePath = public_path('file-templates/import-product.xlsx');

        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        return response()->download($filePath, 'import-product.xlsx', [
            'Content-Type' => mime_content_type($filePath),
            'Content-Length' => filesize($filePath),
        ]);
    }
}
