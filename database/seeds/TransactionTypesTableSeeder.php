<?php

use App\Bank;
use App\TransactionType;
use Illuminate\Database\Seeder;

class TransactionTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if ($rabobank = Bank::where('bic', 'RABONL2U')->first()) {
            $id = $rabobank->id;

            TransactionType::insert([
                ['code' => 'ac', 'bank_id' => $id],
                ['code' => 'ba', 'bank_id' => $id],
                ['code' => 'bc', 'bank_id' => $id],
                ['code' => 'bg', 'bank_id' => $id],
                ['code' => 'bv', 'bank_id' => $id],
                ['code' => 'cb', 'bank_id' => $id],
                ['code' => 'ck', 'bank_id' => $id],
                ['code' => 'db', 'bank_id' => $id],
                ['code' => 'eb', 'bank_id' => $id],
                ['code' => 'ei', 'bank_id' => $id],
                ['code' => 'fb', 'bank_id' => $id],
                ['code' => 'ga', 'bank_id' => $id],
                ['code' => 'gb', 'bank_id' => $id],
                ['code' => 'id', 'bank_id' => $id],
                ['code' => 'kh', 'bank_id' => $id],
                ['code' => 'ma', 'bank_id' => $id],
                ['code' => 'sb', 'bank_id' => $id],
                ['code' => 'sp', 'bank_id' => $id],
                ['code' => 'st', 'bank_id' => $id],
                ['code' => 'tb', 'bank_id' => $id],
                ['code' => 'te', 'bank_id' => $id],
                ['code' => 'wb', 'bank_id' => $id],
            ]);
        }
    }
}
