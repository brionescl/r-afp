<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;

class SuperintendencyTest extends TestCase
{
    /**
     * Get rentability
     *
     * @return void
     */
    public function testGetRentability()
    {
        $date = Carbon::now()->addMonth(-2);
        $year = $date->isoFormat('YYYY');
        $month = $date->isoFormat('MM');

        $response = $this->get("/superintendency/rentability/{$year}/{$month}");
        $response->assertStatus(200);

        $content = $response->decodeResponseJson();
        $this->assertNotEquals(0, $content['CAPITAL']['A']);
    }

    /**
     * Get empty rentability
     *
     * @return void
     */
    public function testGetEmptyRentability()
    {
        $date = Carbon::now()->addMonth(1);
        $year = $date->isoFormat('YYYY');
        $month = $date->isoFormat('MM');
        $response = $this->get("/superintendency/rentability/{$year}/{$month}");

        $emptyInvestmentFunds = [
            'A' => 0,
            'B' => 0,
            'C' => 0,
            'D' => 0,
            'E' => 0
        ];

        $response
            ->assertStatus(200)
            ->assertJson(['CAPITAL' => $emptyInvestmentFunds]);
    }
}
