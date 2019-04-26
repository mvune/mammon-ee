<?php

namespace App;

use App\Account;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
