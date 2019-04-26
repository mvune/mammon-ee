<?php

use App\Bank;
use Illuminate\Database\Seeder;

class BanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bank::insert([
            'bic' => 'RABONL2U',
        ]);
    }
}
