<?php

namespace App;

use App\Account;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{
    public function account()
    {
        return $this->belongsTo(Account::class);
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
}
