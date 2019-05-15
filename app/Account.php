<?php

namespace App;

use App\User;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['name', 'iban'];

    public function setIbanAttribute($value)
    {
        $this->attributes['iban'] = strtoupper(str_replace(' ', '', $value));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function setBalanceToLast()
    {
        if ($lastTransaction = $this->transactions()->latest('serial_number')->first()) {
            $this->balance = $lastTransaction->balance_after_transaction;
            $this->balance_date = $lastTransaction->date;
            $this->save();
        }
    }
}
