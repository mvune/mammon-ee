<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $table = 'accounts';

    protected $fillable = ['name', 'iban'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
