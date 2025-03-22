<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WarehouseCash;
use Illuminate\Http\Request;

class CashController extends Controller
{
    public function index()
    {
        $transactions = WarehouseCash::where('warehouse_id', auth()->user()->warehouse->id)
            ->orderBy('date', 'desc')
            ->get();
        $currentBalance = $transactions->where('transaction_type', 'income')->sum('amount') -
                          $transactions->where('transaction_type', 'expense')->sum('amount');
        return view('warehouse.cash.index', compact('transactions', 'currentBalance'));
    }
}
