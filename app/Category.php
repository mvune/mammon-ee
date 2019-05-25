<?php

namespace App;

use App\Transaction;
use App\TransactionFilter;
use App\Scopes\PriorityDescScope;
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
}
