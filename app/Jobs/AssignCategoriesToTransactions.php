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

        $categories = $this->user->categoriesWithFilters
            ->sortByDesc('priority')
            ->values()->all(); // Reindex.

        foreach ($categories as $category) {
            $transactions = Transaction::whereNull('category_id');
            $transactions->bySide($category->side);

            foreach ($category->transactionFilters as $filter) {
                $target = $filter->transactionFilterTarget->name;

                if (self::isRegex($filter->expression)) {
                    $transactions->where($target, 'regexp', trim($filter->expression, '/'));
                } else {
                    $transactions->where($target, 'like', '%' . $filter->expression . '%');
                }
            }

            $transactions->update(['category_id' => $filter->category_id]);
        }
    }

    private static function isRegex(string $string)
    {
        return Str::startsWith($string, '/') && Str::endsWith($string, '/');
    }
}
