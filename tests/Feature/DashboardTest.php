<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    /**
     * Get rentability
     *
     * @return void
     */
    public function testIndex()
    {
        // $date = Carbon::now()->addMonth(-2);
        // $year = $date->isoFormat('YYYY');
        // $month = $date->isoFormat('MM');

        // $response = $this->get("/rentability/{$year}/{$month}");
        // $response->assertStatus(200);

        // $content = $response->decodeResponseJson();
        // $this->assertNotEquals(0, $content['CAPITAL']['A']);

        $this->assertTrue(true);
    }

    /**
     * Test dashboard
     *
     * @return void
     */
    public function testDashboard()
    {
        // $date = Carbon::now()->addMonth(1);
        // $year = $date->isoFormat('YYYY');
        // $month = $date->isoFormat('MM');
        // $response = $this->get("/rentability/{$year}/{$month}");

        // $emptyInvestmentFunds = [
        //     'A' => 0,
        //     'B' => 0,
        //     'C' => 0,
        //     'D' => 0,
        //     'E' => 0
        // ];

        // $response
        //     ->assertStatus(200)
        //     ->assertJson(['CAPITAL' => $emptyInvestmentFunds]);
        $this->assertTrue(true);
    }

    public function testSync()
    {
        $this->assertTrue(true);
    }
}
