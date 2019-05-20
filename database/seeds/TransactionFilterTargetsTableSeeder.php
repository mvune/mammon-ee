<?php

use App\TransactionFilterTarget;
use Illuminate\Database\Seeder;

class TransactionFilterTargetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransactionFilterTarget::insert([
            ['code' => 'cp_iban', 'name' => 'counterparty_iban'],
            ['code' => 'cp_name', 'name' => 'counterparty_name'],
            ['code' => 'type', 'name' => 'code'],
            ['code' => 'description', 'name' => 'description_1'],
        ]);
    }
}
