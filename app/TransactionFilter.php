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
        return $this->belongsTo(TransactionFilterTarget::class, 'target_id');
    }

    /**
     * Scope a query to order by `categories`.`priority`.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $order
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByCategoryPriority($query, $order = 'desc')
    {
        $order1 = $order === 'asc' ? 'asc' : 'desc';
        $order2 = $order === 'asc' ? 'desc' : 'asc';

        return $query->orderBy('categories.priority', $order1)
                     ->orderBy('categories.created_at', $order2);
    }
}
