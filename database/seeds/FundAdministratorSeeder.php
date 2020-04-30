<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

// @codingStandardsIgnoreLine
class FundAdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('fund_administrators')->insert([
            ['name' => 'CAPITAL', 'created_at' => $now],
            ['name' => 'CUPRUM', 'created_at' => $now],
            ['name' => 'HABITAT', 'created_at' => $now],
            ['name' => 'MODELO', 'created_at' => $now],
            ['name' => 'PLANVITAL', 'created_at' => $now],
            ['name' => 'PROVIDA', 'created_at' => $now],
            ['name' => 'UNO', 'created_at' => $now]
        ]);
    }
}
