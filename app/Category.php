<?php

namespace App;

use App\Transaction;
use App\TransactionFilter;
use App\Scopes\PriorityDescScope;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const SIDE_CREDIT = 'credit';
    const SIDE_DEBET = 'debet';
    const SIDES = [self::SIDE_CREDIT, self::SIDE_DEBET];

    protected $fillable = ['name', 'side', 'priority'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new PriorityDescScope);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function transactionFilters()
    {
        return $this->hasMany(TransactionFilter::class);
    }

    /**
     * Filter by id's.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Http\Request|null  $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByIds($query, Request $request = null)
    {
        return $query->when($request && $request->has('categories'), function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                $ids = explode(',', $request->get('categories'));

                $query
                    ->whereIn('id', $ids)
                    ->when(in_array('null:' . Category::SIDE_DEBET, $ids), function ($query) {
                        return $query->orWhere(function ($query) {
                            $query->whereNull('id')
                                ->where('side', Category::SIDE_DEBET);
                        });
                    })
                    ->when(in_array('null:' . Category::SIDE_CREDIT, $ids), function ($query) {
                        return $query->orWhere(function ($query) {
                            $query->whereNull('id')
                                ->where('side', Category::SIDE_CREDIT);
                        });
                    })
                ;
            });
        });
    }
}
