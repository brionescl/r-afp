<?php

namespace Tests\Feature;

use App\FundAdministrator;
use App\Rentability;
use Carbon\Carbon;
use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    public function testIndex()
    {
        $date = Carbon::now()->addMonth(-1);
        $year = $date->format('Y');
        $month = $date->format('m');

        $response = $this->get('/');

        $response->assertStatus(302);
        $response->assertRedirect("dashboard/{$year}/{$month}");
    }

    public function testDashboard()
    {
        /** @var FundAdministrator $fundAdministrator */
        $fundAdministrator = factory(FundAdministrator::class)->create();

        /** @var Rentability $rentability */
        $rentability = factory(Rentability::class)->create([
            'fund_administrator_id' => $fundAdministrator->id
        ]);

        $date = Carbon::now();
        $year = $date->format('Y');
        $month = $date->format('m');
        $response = $this->get("dashboard/{$year}/{$month}");

        $response->assertStatus(200);
        $response->assertSeeText($fundAdministrator->name);
        $response->assertSeeText($rentability->investment_fund);
    }

    public function testDashboardWithInvalidDate()
    {
        /** @var FundAdministrator $fundAdministrator */
        $fundAdministrator = factory(FundAdministrator::class)->create();

        /** @var Rentability $rentability */
        $rentability = factory(Rentability::class)->create([
            'fund_administrator_id' => $fundAdministrator->id,
            'date' => Carbon::now()->addMonth(-1)->format('Y-m-01')
        ]);

        $response = $this->get("dashboard/2000/13");

        $response->assertStatus(200);
        $response->assertSeeText($fundAdministrator->name);
        $response->assertSeeText($rentability->investment_fund);
    }

    public function testSync()
    {
        $date = Carbon::now()->addMonth(-1);
        $year = $date->format('Y');
        $month = $date->format('m');

        $response = $this->get("dashboard/{$year}/{$month}/sync");

        $response->assertStatus(302);
        $response->assertRedirect("dashboard/{$year}/{$month}");

        $rentabilities = Rentability::all();
        $this->assertTrue($rentabilities->isNotEmpty());
    }
}
