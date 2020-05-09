<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    public function index(Request $request)
    {
        $fundAdministrators = FundAdministrator::all();
        $superintendency = new Superintendency;

        $years = range(2005, Carbon::now()->year);
        $year = $request->input('year');
        $month = $request->input('month');

        $date = Carbon::now()->addMonth(-1);
        if (!empty($year) && !empty($month)) {
            $date = Carbon::createFromDate($year, $month, 1);
        }

        $rentability = $superintendency->getRentability($date);
        $investmentFunds = $superintendency->getInvestmentFunds();

        return view('dashboard', [
            'fundAdministrators' => $fundAdministrators,
            'rentability' => $rentability,
            'investmentFunds' => $investmentFunds,
            'years' => array_combine($years, $years),
            'year' => $date->year,
            'months' => [
                1 => 'Enero',
                2 => 'Febrero',
                3 => 'Marzo',
                4 => 'Abril',
                5 => 'Mayo',
                6 => 'Junio',
                7 => 'Julio',
                8 => 'Agosto',
                9 => 'Septiembre',
                10 => 'Octubre',
                11 => 'Noviembre',
                12 => 'Diciembre'
            ],
            'month' => $date->month
        ]);
    }
}
