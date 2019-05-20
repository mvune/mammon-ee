<?php

namespace App;

use App\User;
use App\Category;
use App\TransactionFilterTarget;
use Illuminate\Database\Eloquent\Model;

class TransactionFilter extends Model
{
    protected $fillable = ['target_id', 'expression'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactionFilterTarget()
    {
        return $this->belongsTo(TransactionFilterTarget::class);
    }
}
