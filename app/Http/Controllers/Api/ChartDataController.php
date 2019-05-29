<?php

namespace App\Http\Controllers\Api;

use App\Transaction;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChartDataController extends Controller
{
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

    public function getSheetData(Request $request)
    {
        $rows = [];
        $currentSide = 'debet';

        foreach (Auth::user()->categoriesWithFilters()->get() as $category) {
            $result = Transaction::ofAuthUser()
                ->select(
                    DB::raw('MONTH(date) as month'),
                    DB::raw('ROUND(SUM(amount), 2) as amount')
                )
                ->where(DB::raw('YEAR(date)'), 2019)
                ->where('category_id', $category->id)
                ->groupBy(DB::raw('YEAR(date)'))
                ->groupBy(DB::raw('MONTH(date)'))
                ->groupBy('category_id')
                ->get();

            $row = array_combine(Arr::pluck($result, 'month'), Arr::pluck($result, 'amount'));
            $row['year_total'] = array_sum($row);
            $row['category'] = $category->name;

            if ($category->side !== $currentSide) {
                $rows[] = $this->getHeaderRow($category->side);
            }

            $currentSide = $category->side;
            $rows[] = $row;
        }

        $rows[] = $this->getHeaderRow('net');

        return $rows;
    }

    private function getHeaderRow(string $side)
    {
        $row = [];
        $row['category'] = __("misc.category_sides.{$side}");
        $date = Carbon::create();
        
        do {
            $row[$date->month] = $date->monthName;
            $date->addMonth();
        } while ($date->month > 1);
        
        $row['year_total'] = __('misc.year_total');
        $row['is_header_row'] = true;

        return $row;
    }
}
