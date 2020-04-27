<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class FundAdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fund_administrators')->insert([
            ['name' => 'CAPITAL', 'created_at' => Carbon::now()],
            ['name' => 'CUPRUM', 'created_at' => Carbon::now()],
            ['name' => 'HABITAT', 'created_at' => Carbon::now()],
            ['name' => 'MODELO', 'created_at' => Carbon::now()],
            ['name' => 'PLANVITAL', 'created_at' => Carbon::now()],
            ['name' => 'PROVIDA', 'created_at' => Carbon::now()],
            ['name' => 'UNO', 'created_at' => Carbon::now()]
        ]);
    }
}
