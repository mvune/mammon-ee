<?php

namespace App;

use App\User;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $table = 'accounts';

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
}
