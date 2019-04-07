<?php

use App\BankAccount;
use Illuminate\Database\Seeder;

class BankAccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(BankAccount::class, 8)->create();
    }
}
