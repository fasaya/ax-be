<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Enums\TransactionStatus;
use App\Exports\TransactionExport;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Http\Requests\V1\TransactionExportRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\V1\TransactionResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\V1\TransactionCollection;
use App\Http\Requests\V1\TransactionStoreRequest;
use App\Http\Requests\V1\TransactionUpdateRequest;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Transaction::paginate(10);
        return new TransactionCollection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionStoreRequest $request)
    {
        $data = $request->only([
            'invoice_no',
            'product_id',
            'quantity',
            // 'status',
            'paid_at',
        ]);

        $product = Product::find($data['product_id']);

        if ($product->stock < $request->quantity) {
            return ResponseHelper::sendErrorResponse('Not enough stock', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $price = $product->discounted_price > 0 ? $product->discounted_price : $product->price;

        $data['status'] = TransactionStatus::Paid;
        $data['item_price'] = $price;
        $data['total_price'] =  $price * $data['quantity'];

        $transaction = Transaction::create($data);

        return TransactionResource::make(Transaction::find($transaction->id));
    }

    /**
     * Display the specified resource.
     */
    public function show($transaction)
    {
        $transaction = Transaction::find($transaction);

        if (!$transaction) {
            return ResponseHelper::sendErrorNotFoundResponse();
        }

        return TransactionResource::make($transaction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionUpdateRequest $request, $transaction)
    {
        $transaction = Transaction::find($transaction);

        if (!$transaction) {
            return ResponseHelper::sendErrorNotFoundResponse();
        }

        // TO DO: check product stock
        // ...

        $data = $request->only([
            'invoice_no',
            // 'product_id',
            // 'quantity',
            'item_price',
            'total_price',
            // 'status',
            'paid_at',
        ]);

        $transaction->update($data);

        $transaction = Transaction::find($transaction->id);

        return TransactionResource::make($transaction);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($transaction)
    {
        $transaction = Transaction::find($transaction);

        if (!$transaction) {
            return ResponseHelper::sendErrorNotFoundResponse();
        }

        $transaction->delete();

        return ResponseHelper::sendResponse(null, 'Data successfully deleted');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function export(TransactionExportRequest $request)
    {
        return Excel::download(
            new TransactionExport($request->start_date, $request->end_date),
            'transaction.xlsx'
        );
    }
}
