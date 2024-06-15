<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionExport implements ShouldAutoSize, FromView
{

    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        $data = Transaction::query();

        if (isset($this->startDate, $this->endDate)) {
            $data = $data->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        $data = $data->get();

        return view('admin.exports.transactions', compact('data'));
    }
}
