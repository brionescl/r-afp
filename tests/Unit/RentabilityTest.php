<?php

namespace Tests\Unit;

use App\FundAdministrator;
use App\Rentability;
use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RentabilityTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    public function testGetFundAdministrator()
    {
        /** @var Rentability $rentability */
        $rentability = factory(Rentability::class)->create();

        /** @var FundAdministrator $fundAdministrator */
        $fundAdministrator = $rentability->fundAdministrator()->get();

        $this->assertTrue($fundAdministrator->isNotEmpty());
    }
}
