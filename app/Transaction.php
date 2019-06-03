<?php

namespace App;

use App\Account;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope a query to only include transactions of the authenticated user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfAuthUser($query)
    {
        return $query
            ->from('transactions')
            ->join('accounts', 'accounts.id', '=', 'transactions.account_id')
            ->where('accounts.user_id', Auth::user()->id);
    }

    /**
     * Filter by accounts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Http\Request|null  $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByAccounts($query, Request $request = null)
    {
        return $query->when($request && $request->has('accounts'), function ($query) use ($request) {
            return $query->whereIn('account_id', explode(',', $request->get('accounts')));
        });
    }

    /**
     * Filter by categories.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Http\Request|null  $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategories($query, Request $request = null)
    {
        return $query->when($request && $request->has('categories'), function ($query) use ($request) {
            return $query->where(function ($query) use ($request) {
                $ids = explode(',', $request->get('categories'));

                $query
                    ->whereIn('category_id', $ids)
                    ->when(in_array('null:' . Category::SIDE_DEBET, $ids), function ($query) {
                        return $query->orWhere(function ($query) {
                            $query->whereNull('category_id')
                                ->where('amount', '>', 0);
                        });
                    })
                    ->when(in_array('null:' . Category::SIDE_CREDIT, $ids), function ($query) {
                        return $query->orWhere(function ($query) {
                            $query->whereNull('category_id')
                                ->where('amount', '<', 0);
                        });
                    })
                ;
            });
        });
    }
}
