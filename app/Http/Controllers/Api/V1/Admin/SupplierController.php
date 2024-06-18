<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Imports\SupplierImport;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\V1\SupplierResource;
use App\Http\Resources\V1\SupplierCollection;
use App\Http\Requests\V1\SupplierStoreRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\V1\SupplierImportRequest;
use App\Http\Requests\V1\SupplierUpdateRequest;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Supplier::query();

        if ($request->search) {
            $data->whereAny([
                'code',
                'name',
                'address',
                'email',
                'pic_name',
                'pic_email',
            ], 'LIKE', '%' . $request->search . '%');
        }

        $data = $data->orderBy('id', 'DESC');

        $data = $request->per_page ? $data->paginate($request->per_page) : $data->get();

        return new SupplierCollection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierStoreRequest $request)
    {
        $data = $request->only([
            'code',
            'name',
            'address',
            'phone',
            'email',
            'npwp',
            'pic_name',
            'pic_phone',
            'pic_email',
            'preferred_payout',
        ]);

        $supplier = Supplier::create($data);

        return SupplierResource::make($supplier);
    }

    /**
     * Display the specified resource.
     */
    public function show($supplier)
    {
        $supplier = Supplier::find($supplier);

        if (!$supplier) {
            return ResponseHelper::sendErrorNotFoundResponse();
        }

        return SupplierResource::make($supplier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierUpdateRequest $request, $supplier)
    {
        $supplier = Supplier::find($supplier);

        if (!$supplier) {
            return ResponseHelper::sendErrorNotFoundResponse();
        }

        $data = $request->only([
            'code',
            'name',
            'address',
            'phone',
            'email',
            'npwp',
            'pic_name',
            'pic_phone',
            'pic_email',
            'preferred_payout',
        ]);

        $supplier->update($data);

        $supplier = Supplier::find($supplier->id);

        return SupplierResource::make($supplier);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($supplier)
    {
        $supplier = Supplier::find($supplier);

        if (!$supplier) {
            return ResponseHelper::sendErrorNotFoundResponse();
        }

        try {
            $supplier->delete();
        } catch (\Throwable $th) {
            return ResponseHelper::sendErrorResponse("Failed to delete", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return ResponseHelper::sendResponse(null, 'Data successfully deleted');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function import(SupplierImportRequest $request)
    {
        Excel::import(new SupplierImport, $request->file('file'));

        return ResponseHelper::sendResponse(null, 'Data successfully imported');
    }

    public function downloadImportTemplate(Request $request)
    {
        $filePath = public_path('file-templates/import-supplier.xlsx');

        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        return response()->download($filePath, 'import-supplier.xlsx', [
            'Content-Type' => mime_content_type($filePath),
            'Content-Length' => filesize($filePath),
        ]);
    }
}
