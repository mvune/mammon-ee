<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Transaction;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChartDataController extends Controller
{
    public function getFiltersData(Request $request)
    {
        $dateFilter = [
            'fromDate' => Transaction::ofAuthUser()->min('date'),
            'toDate' => Transaction::ofAuthUser()->max('date'),
        ];

        return [ 'dateFilter' => $dateFilter ];
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

    public function getSheetData(Request $request)
    {
        $result = Transaction::ofAuthUser()->select(
            DB::raw('MIN(YEAR(date)) as fromYear'),
            DB::raw('MAX(YEAR(date)) as toYear')
        )->first();

        $y = $result->fromYear;
        $toYear = $result->toYear;
        $years = [];

        for ($y; $y <= $toYear; $y++) {
            $years[$y] = $this->getSheetDataForYear($y);
        }

        return $years;
    }

    private function getSheetDataForYear(int $year)
    {
        $rows = [];
        $currentSide = Category::SIDE_DEBET;
        $categories = Auth::user()->categoriesWithFilters()->get();

        foreach ($categories as $category) {
            $row = $this->createRow($year, $category);

            if ($category->side !== $currentSide || $category->is($categories->last())) {
                $rows[] = $this->createRow($year, $this->makeNoCategory($currentSide));
                $rows[] = $this->createRow($year, $this->makeTotalsCategory($currentSide));
            }
            if ($category->side !== $currentSide) {
                $rows[] = $this->createHeaderRow($category->side);
                $currentSide = $category->side;
            }

            $rows[] = $row;
        }

        $rows[] = $this->createHeaderRow('net');
        $rows[] = $this->createRow($year, $this->makeTotalsCategory(Category::SIDE_DEBET));
        $rows[] = $this->createRow($year, $this->makeTotalsCategory(Category::SIDE_CREDIT));
        $rows[] = $this->createRow($year, $this->makeTotalsCategory('net'));

        return $rows;
    }

    private function makeNoCategory(string $side)
    {
        $noCategory = new Category;
        $noCategory->name = __('misc.no_category');
        $noCategory->side = $side;

        return $noCategory;
    }

    private function makeTotalsCategory(string $side) {
        $totalsCategory = new Category;
        $totalsCategory->id = 0;
        $totalsCategory->name = __("misc.total_{$side}");
        $totalsCategory->side = $side;
        
        return $totalsCategory;
    }

    private function createRow(int $year, Category $category)
    {
        $result = Transaction::ofAuthUser()
            ->select(
                DB::raw('MONTH(date) as month'),
                DB::raw('ROUND(SUM(amount), 2) as amount')
            )
            ->where(DB::raw('YEAR(date)'), $year)
            ->when(!$category->id, function ($query) use ($category) {
                if ($category->side === Category::SIDE_DEBET) {
                    return $query->where('amount', '>', 0);
                } else if ($category->side === Category::SIDE_CREDIT) {
                    return $query->where('amount', '<', 0);
                } else {
                    return $query;
                }
            })
            ->when($category->id !== 0, function ($query) use ($category) {
                return $query
                    ->where('category_id', $category->id)
                    ->groupBy('category_id');
            })
            ->groupBy(DB::raw('YEAR(date)'))
            ->groupBy(DB::raw('MONTH(date)'))
            ->get();

        $row = array_combine(Arr::pluck($result, 'month'), Arr::pluck($result, 'amount'));
        $row['year_total'] = array_sum($row);
        $row['category'] = $category->name;

        return $row;
    }

    private function createHeaderRow(string $side)
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
