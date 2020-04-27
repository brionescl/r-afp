<?php

namespace App;

use Carbon\Carbon;
use Weidner\Goutte\GoutteFacade as Goutte;

class Superintendency
{
    /**
     * URL pension superintendency
     *
     * @var string
     */
    private $url;

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
    private $investmentFunds = [];

    /**
     * Scraping date
     *
     * @var Carbon
     */
    private $scrapingDate = null;

    public function __construct()
    {
        $this->url = 'https://www.spensiones.cl/apps/rentabilidad/getRentabilidad.php';
        $this->fundAdministrators = [
            'CAPITAL',
            'CUPRUM',
            'HABITAT',
            'MODELO',
            'PLANVITAL',
            'PROVIDA',
            'UNO'
        ];
        $this->investmentFunds = ['A', 'B', 'C', 'D', 'E'];
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

        (Goutte::request(
            'POST',
            "{$this->url}?tiprent=FP&template=0",
            [
                'aaaa' => $this->scrapingDate->isoFormat('YYYY'),
                'mm' => $this->scrapingDate->isoFormat('MM'),
                'btn' => 'Buscar'
            ]
        ))->filter('table')->each(function ($table) use (&$data) {
            $th = $table->filter('th')->first()->text('default', true);
            $investmentFund = $this->getInvestmentFund($th);

            if (is_null($investmentFund)) {
                return;
            }

            $table->filter('tr')->each(function ($tr) use (&$data, $investmentFund) {
                $td = $tr->filter('td')->first()->text('default', true);
                $fundAdministrator = $this->getFundAdministrator($td);

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
     * Get investment fund
     *
     * @param string $str
     * @return string|null
     */
    private function getInvestmentFund($str)
    {
        foreach ($this->investmentFunds as $investmentFund) {
            if (stripos($str, "FONDO TIPO {$investmentFund}") !== false) {
                return $investmentFund;
            }
        }

        return null;
    }

    /**
     * Get fund administrator
     *
     * @param string $str
     * @return string|null
     */
    private function getFundAdministrator($str)
    {
        foreach ($this->fundAdministrators as $fundAdministrator) {
            if ($fundAdministrator == $str) {
                return $fundAdministrator;
            }
        }

        return null;
    }
}
