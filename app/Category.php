<?php

namespace App;

use App\TransactionFilter;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const SIDE_CREDIT = 'credit';
    const SIDE_DEBET = 'debet';
    const SIDES = [self::SIDE_CREDIT, self::SIDE_DEBET];

    protected $fillable = ['name', 'side', 'priority'];

    public function transactionFilters()
    {
        return $this->hasMany(TransactionFilter::class);
    }
}
