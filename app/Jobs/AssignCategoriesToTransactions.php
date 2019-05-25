<?php

namespace App\Jobs;

use App\User;
use App\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AssignCategoriesToTransactions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Assign categories to transactions.
     *
     * @return void
     */
    public function handle()
    {
        Transaction::whereRaw(1)->update(['category_id' => null]);

        $categories = $this->user->transactionFilters->groupBy('category_id');

        foreach ($categories as $categoryWithFilters) {
            $query = Transaction::whereNull('category_id');

            foreach ($categoryWithFilters as $filter) {
                $target = $filter->transactionFilterTarget->name;

                if (self::isRegex($filter->expression)) {
                    $query->where($target, 'regexp', trim($filter->expression, '/'));
                } else {
                    $query->where($target, 'like', '%' . $filter->expression . '%');
                }
            }

            $query->update(['category_id' => $filter->category_id]);
        }
    }

    private static function isRegex(string $string)
    {
        return Str::startsWith($string, '/') && Str::endsWith($string, '/');
    }
}
