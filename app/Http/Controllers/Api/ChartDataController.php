<?php

namespace App\Http\Controllers\Api;

use Str;
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
    const DEBET = Category::SIDE_DEBET;
    const CREDIT = Category::SIDE_CREDIT;

    private $request;

    public function getBoundaryDates(Request $request)
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
        $this->request = $request;

        $years = Transaction::ofAuthUser()->select(
            DB::raw('MIN(YEAR(date)) as fromYear'),
            DB::raw('MAX(YEAR(date)) as toYear')
        )->first();

        $y = $years->fromYear;
        $toYear = $years->toYear;
        $sheetData = [];

        for ($y; $y <= $toYear; $y++) {
            $sheetData[$y] = $this->getSheetDataForYear($y);
        }

        return $sheetData;
    }

    private function getSheetDataForYear(int $year)
    {
        $rows = [];
        $categories = Auth::user()->categories()->byIds($this->request)->get();
        $debetCategories = $categories->filter(function ($item) { return $item->side === self::DEBET; });
        $creditCategories = $categories->filter(function ($item) { return $item->side === self::CREDIT; });

        // Debet
        foreach ($debetCategories as $category) {
            $rows[] = $this->createRow($year, $category);
        }

        if ($this->isNoCategorySelected(self::DEBET)) {
            $rows[] = $this->createRow($year, $this->makeNoCategory(self::DEBET));
        }
        $rows[] = $this->createTotalsRow($year, self::DEBET);

        // Credit
        $rows[] = $this->createHeaderRow(self::CREDIT);

        foreach ($creditCategories as $category) {
            $rows[] = $this->createRow($year, $category);
        }

        if ($this->isNoCategorySelected(self::CREDIT)) {
            $rows[] = $this->createRow($year, $this->makeNoCategory(self::CREDIT));
        }
        $rows[] = $this->createTotalsRow($year, self::CREDIT);

        // Net
        $rows[] = $this->createHeaderRow('net');
        $rows[] = $this->createSubtotalsRow($year, self::DEBET);
        $rows[] = $this->createSubtotalsRow($year, self::CREDIT);
        $rows[] = $this->createTotalsRow($year, 'net');

        return $rows;
    }

    /**
     * Makes a `Category` object that represent the category 'Other'.
     *
     * @param string  $side
     * @return Category
     */
    private function makeNoCategory(string $side)
    {
        $noCategory = new Category;
        $noCategory->id = null;
        $noCategory->name = __('misc.no_category');
        $noCategory->side = $side;

        return $noCategory;
    }

    /**
     * Makes a `Category` object that represents the category 'Totals'.
     * Note that `id` is int 0.
     *
     * @param string  $side
     * @return Category
     */
    private function makeTotalsCategory(string $side) {
        $totalsCategory = new Category;
        $totalsCategory->id = 0;
        $totalsCategory->name = __("misc.total_{$side}");
        $totalsCategory->side = $side;
        
        return $totalsCategory;
    }

    private function isNoCategorySelected(string $side)
    {
        $ids = $this->request->get('categories');

        return is_null($ids) || !is_null($ids) && Str::contains($ids, "null:{$side}");
    }

    private function createRow(int $year, Category $category)
    {
        $result = Transaction::ofAuthUser()
            ->select(
                DB::raw('MONTH(date) as month'),
                DB::raw('ROUND(SUM(amount), 2) as amount')
            )
            ->ByAccounts($this->request)
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
            ->when($category->id === 0, function ($query) {
                return $query->byCategories($this->request);
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
        $row['year_total'] = round(array_sum($row), 2);
        $row['category'] = $category->name;
        $row['side'] = $category->side;

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

    private function createTotalsRow(int $year, string $side)
    {
        return $this->createRow($year, $this->makeTotalsCategory($side))
            + [ 'is_totals_row' => true ]
            + ($side === 'net' ? [ 'is_net_row' => true ] : []);
    }

    private function createSubtotalsRow(int $year, string $side)
    {
        return $this->createRow($year, $this->makeTotalsCategory($side))
            + [ 'is_subtotals_row' => true ];
    }
}
