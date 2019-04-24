<?php

namespace App;

use App\BankAccount;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }
}
