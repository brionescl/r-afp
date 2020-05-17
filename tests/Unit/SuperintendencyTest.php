<?php

namespace Tests\Unit;

use App\Rentability;
use App\Superintendency;
use Carbon\Carbon;
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
        $date = Carbon::now()->addMonth(-2);
        $superintendency = new Superintendency;
        $superintendency->syncRentabilities($date);
        $rentabilities = Rentability::ofDate($date)->get();
        $this->assertFalse($rentabilities->isEmpty());
    }

    public function testSyncRentabilitiesReturnsEmptyInvestmentFunds()
    {
        $date = Carbon::now()->addMonth(2);
        $superintendency = new Superintendency;
        $superintendency->syncRentabilities($date);
        $rentabilities = Rentability::ofDate($date)->get();
        $this->assertTrue($rentabilities->isEmpty());
    }

    public function testSyncRentabilitiesWhereNotAllFundAdministratorsExist()
    {
        $date = Carbon::createFromDate(2005, 8, 1);
        $superintendency = new Superintendency;
        $superintendency->syncRentabilities($date);
        $rentabilities = Rentability::ofDate($date)->get();
        $this->assertFalse($rentabilities->isEmpty());
    }
}
