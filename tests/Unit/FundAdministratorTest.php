<?php

namespace Tests\Unit;

use App\FundAdministrator;
use App\Rentability;
use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FundAdministratorTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    public function testGetRentabilities()
    {
        /** @var FundAdministrator $fundAdministrator */
        $fundAdministrator = factory(FundAdministrator::class)->create();

        factory(Rentability::class)->create([
            'fund_administrator_id' => $fundAdministrator->id
        ]);

        $rentabilities = $fundAdministrator->rentabilities()->get();

        $this->assertTrue($rentabilities->isNotEmpty());
    }
}
