<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\FundAdministrator;
use App\Rentability;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Rentability::class, function (Faker $faker) {
    return [
        'fund_administrator_id' => factory(FundAdministrator::class),
        'investment_fund' => strtoupper($faker->lexify('?')),
        'date' => Carbon::now()->format('Y-m-1'),
        'rentability' => $faker->lexify('#.##'),
        'created_at' => Carbon::now()->format('Y-m-d H:i:s')
    ];
});
