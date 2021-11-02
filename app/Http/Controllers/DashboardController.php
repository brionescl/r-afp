<?php

namespace App\Http\Controllers;

use App\Superintendency;
use App\Rentability;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Years to show in form
     *
     * @var array
     */
    private $years;

    /**
     * Months to show in form
     *
     * @var array
     */
    private $months;

    /**
     * Default date
     *
     * @var Carbon
     */
    private $defaultDate;

    public function __construct()
    {
        $this->defaultDate = Carbon::now()->addMonth(-1);
        $this->years = range(2005, $this->defaultDate->format('Y'));
        $this->months = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        ];
    }

    public function index(): RedirectResponse
    {
        return redirect()->route('dashboard', [
            'year' => $this->defaultDate->format('Y'),
            'month' => $this->defaultDate->format('m')
        ]);
    }

    /**
     * Show dashboard
     *
     * @param int $year
     * @param int $month
     * @return view
     */
    public function dashboard($year = 0, $month = 0): View
    {
        $date = $this->getValidDate($year, $month);
        $rentability = new Rentability();
        $rentabilities = $rentability->groupByFundAdministrator(Rentability::ofDate($date));
        $rentabilitiesLast12Months = $rentability->groupByFundAdministrator(Rentability::ofLast12Months($date));

        return view(
            'dashboard',
            [
                'rentabilities' => $rentabilities,
                'rentabilitiesLast12Months' => $rentabilitiesLast12Months,
                'years' => array_combine($this->years, $this->years),
                'yearSelected' => $date->format('Y'),
                'months' => $this->months,
                'monthSelected' => $date->format('m')
            ]
        );
    }

    public function sync($year = 0, $month = 0): RedirectResponse
    {
        $date = $this->getValidDate($year, $month);
        (new Superintendency)->syncRentabilities($date);

        return redirect()->route('dashboard', [
            'year' => $date->format('Y'),
            'month' => $date->format('m')
        ]);
    }

    private function getValidDate($year = 0, $month = 0): Carbon
    {
        if (!in_array($year, $this->years)) {
            $year = $this->defaultDate->format('Y');
        }

        if (!in_array($month, array_keys($this->months))) {
            $month = $this->defaultDate->format('m');
        }

        return Carbon::createFromDate($year, $month, 1);
    }
}
