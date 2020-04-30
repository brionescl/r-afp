<?php

namespace App;

use App\FundAdministrator;
use Carbon\Carbon;
use Weidner\Goutte\GoutteFacade as Goutte;

class Superintendency
{
    /**
     * URL pension superintendency
     *
     * @var string
     */
    private $url = 'https://www.spensiones.cl/apps/rentabilidad/getRentabilidad.php';

    /**
     * List of fund administrator
     *
     * @var array
     */
    private $fundAdministrators = [];

    /**
     * List of investment fund
     *
     * @var array
     */
    private $investmentFunds = ['A', 'B', 'C', 'D', 'E'];

    /**
     * Scraping date
     *
     * @var Carbon
     */
    private $scrapingDate = null;

    public function __construct()
    {
        $this->setFundAdministrators();
    }

    /**
     * Set array of fund administrators
     *
     * @return void
     */
    private function setFundAdministrators()
    {
        $fundAdministrators = FundAdministrator::all(['name']);
        foreach ($fundAdministrators as $fundAdministrator) {
            $this->fundAdministrators[] = strtoupper($fundAdministrator->name);
        }
    }

    /**
     * Get rentability from scraper
     *
     * @param Carbon $date
     * @return array
     */
    public function getRentability(Carbon $date)
    {
        $this->scrapingDate = $date;
        return $this->scraping();
    }

    /**
     * Scraping page of pension superintendency
     * Get data of rentability
     *
     * @return array
     */
    private function scraping()
    {
        $data = [];
        $year = $this->scrapingDate->isoFormat('YYYY');
        $month = $this->scrapingDate->isoFormat('MM');

        (Goutte::request(
            'POST',
            "{$this->url}?tiprent=FP&template=0",
            [
                'aaaa' => $year,
                'mm' => $month,
                'btn' => 'Buscar'
            ]
        ))->filter('table')->each(function ($table) use (&$data) {
            $th = $table->filter('th')->first()->text('default', true);
            $investmentFund = $this->extractInvestmentFund($th);

            if (is_null($investmentFund)) {
                return;
            }

            $table->filter('tr')->each(function ($tr) use (&$data, $investmentFund) {
                $td = $tr->filter('td')->first()->text('default', true);
                $fundAdministrator = $this->extractFundAdministrator($td);

                if (is_null($fundAdministrator)) {
                    return;
                }

                $rentability = str_replace(',', '.', $tr->filter('td')->eq(1)->text('0', true));
                $data[$fundAdministrator][$investmentFund] = (float) $rentability;
            });
        });

        return $data;
    }

    /**
     * Extract investment fund
     *
     * @param string $str
     * @return string|null
     */
    private function extractInvestmentFund($str)
    {
        foreach ($this->investmentFunds as $investmentFund) {
            if (stripos($str, "FONDO TIPO {$investmentFund}") !== false) {
                return $investmentFund;
            }
        }

        return null;
    }

    /**
     * Extract fund administrator
     *
     * @param string $str
     * @return string|null
     */
    private function extractFundAdministrator($str)
    {
        foreach ($this->fundAdministrators as $fundAdministrator) {
            if ($fundAdministrator == $str) {
                return $fundAdministrator;
            }
        }

        return null;
    }
}
