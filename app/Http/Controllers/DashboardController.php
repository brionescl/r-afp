<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\FundAdministrator;
use App\Superintendency;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Get rentability
     *
     * @param int $year
     * @param int $month
     * @return string json
     */
    public function index()
    {
        $fundAdministrators = FundAdministrator::all();
        $superintendency = new Superintendency;
        $rentability = $superintendency->getRentability(Carbon::now()->addMonth(-2));
        $investmentFunds = $superintendency->getInvestmentFunds();

        return view('dashboard', [
            'fundAdministrators' => $fundAdministrators,
            'rentability' => $rentability,
            'investmentFunds' => $investmentFunds
        ]);
    }
}
