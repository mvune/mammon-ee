<?php

use App\Account;
use App\User;
use Faker\Generator as Faker;

$factory->define(Account::class, function (Faker $faker) {
    return [
        'name' => $faker->firstname . ' rekening',
        'iban' => $faker->iban('nl'),
        'user_id' => User::inRandomOrder()->first()->id,
    ];
});
