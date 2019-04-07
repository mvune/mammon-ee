<?php

use App\BankAccount;
use Faker\Generator as Faker;

$factory->define(BankAccount::class, function (Faker $faker) {
    return [
        'name' => $faker->firstname . ' rekening',
        'iban' => $faker->iban('nl'),
        'user_id' => $faker->numberBetween(1, 10),
    ];
});
