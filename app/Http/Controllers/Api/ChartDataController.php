<?php

namespace App\Http\Controllers\Api;

use App\Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartDataController extends Controller
{
    public function getAmounts(Request $request)
    {
        return Transaction::ofAuthUser()
            ->select(
                DB::raw('YEAR(date) as year'),
                DB::raw('MONTH(date) as month'),
                DB::raw('ROUND(SUM(amount), 2) as amount')
            )
            ->groupBy('year', 'month', 'user_id')
            ->get();
    }

    public function getBalances(Request $request)
    {
        return Transaction::ofAuthUser()
            ->select('date', 'balance_after_transaction as balance')
            ->whereIn('serial_number', function ($query) {
                return (new Transaction)->scopeOfAuthUser($query)
                    ->select(DB::raw('MAX(serial_number)'))
                    ->groupBy('date');
            })
            ->orderBy('date')
            ->get();
    }
}
