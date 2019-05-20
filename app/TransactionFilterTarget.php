<?php

namespace App;

use App\TransactionFilter;
use Illuminate\Database\Eloquent\Model;

class TransactionFilterTarget extends Model
{
    public function transactionFilter()
    {
        return $this->hasMany(TransactionFilter::class);
    }
}
