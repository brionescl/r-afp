<?php

namespace Tests\Unit;

use App\FundAdministrator;
use App\Superintendency;
use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SuperintendencyTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    public function testGetFundAdministrators()
    {
        $superintendency = new Superintendency;
        $fundAdministrator = $superintendency->getFundAdministrators()->first();

        $this->assertEquals('CAPITAL', $fundAdministrator->name);
    }

    public function testGetInvestmentFunds()
    {
        $this->assertEquals(
            ['A', 'B', 'C', 'D', 'E'],
            (new Superintendency)->getInvestmentFunds()
        );
    }

    public function testSyncRentabilities()
    {
        $this->assertTrue(true);
    }
}
