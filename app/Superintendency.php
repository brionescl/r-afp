<?php

namespace App;

use App\FundAdministrator;
use Carbon\Carbon;
use Weidner\Goutte\GoutteFacade as Goutte;

use Illuminate\Database\Eloquent\Collection;

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
     * @var Collection|FundAdministrator[]
     */
    private $fundAdministrators;

    /**
     * List of investment fund
     *
     * @var array
     */
    private $investmentFunds = ['A', 'B', 'C', 'D', 'E'];

    public function __construct()
    {
        $this->fundAdministrators = FundAdministrator::all();
    }

    /**
     * Get collection of fund administrators
     *
     * @return Collection|FundAdministrator[]
     */
    public function getFundAdministrators(): Collection
    {
        return $this->fundAdministrators;
    }

    /**
     * Get array of investment funds
     *
     * @return array
     */
    public function getInvestmentFunds(): array
    {
        return $this->investmentFunds;
    }

    /**
     * Get rentabilities of all fund administrators by date
     *
     * @param Carbon $date
     * @return array
     */
    public function getRentabilitiesByDate(Carbon $date): array
    {
        $rentabilities = [];

        foreach ($this->fundAdministrators as $fundAdministrator) {
            $rentabilitiesOfFunds = $fundAdministrator->getRentabilitiesOfFundsByDate($date);

            if (!$rentabilitiesOfFunds->isEmpty()) {
                foreach ($rentabilitiesOfFunds as $rentability) {
                    dd($rentability->rentability);
                }
            }
        }

        return $rentabilities;
    }

    /**
     * Sync fund rentabilities of all fund administrators
     * by specific date
     *
     * @param Carbon $date
     * @return void
     */
    public function syncRentabilities(Carbon $date): void
    {
        $data = $this->scraping($date);

        if ($this->allRentabilitiesAreEmpty($data)) {
            return;
        }

        foreach ($this->fundAdministrators as $fundAdministrator) {
            $fundAdministratorName = strtoupper($fundAdministrator->name);

            if (!isset($data[$fundAdministratorName])) {
                continue;
            }

            foreach ($this->investmentFunds as $investmentFund) {
                $rentability = Rentability::firstOrNew([
                    'fund_administrator_id' => $fundAdministrator->id,
                    'investment_fund' => $investmentFund,
                    'date' => $date->format('Y-m-01')
                ]);
                $rentability->rentability = $data[$fundAdministratorName][$investmentFund];
                $rentability->save();
            }
        }
    }

    /**
     * Scraping page of pension superintendency
     * Get data of rentability
     *
     * @param Carbon $date
     * @return array
     */
    private function scraping(Carbon $date): array
    {
        $data = [];

        (Goutte::request(
            'POST',
            "{$this->url}?tiprent=FP&template=0",
            [
                'aaaa' => $date->format('Y'),
                'mm' => $date->format('m'),
                'btn' => 'Buscar'
            ]
        ))->filter('table')->each(function ($table) use (&$data) {
            $th = $table->filter('th')->first()->text('default', true);
            $investmentFund = $this->extractInvestmentFund($th);

            if (empty($investmentFund)) {
                return;
            }

            $table->filter('tr')->each(function ($tr) use (&$data, $investmentFund) {
                $td = $tr->filter('td')->first()->text('default', true);
                $fundAdministrator = $this->extractFundAdministrator($td);

                if (empty($fundAdministrator)) {
                    return;
                }

                $rentability = str_replace(',', '.', $tr->filter('td')->eq(1)->text('0', true));
                $data[$fundAdministrator][$investmentFund] = (float) $rentability;
            });
        });

        return $data;
    }

    /**
     * If all rentabilities are empty, then return true
     *
     * @param array $data
     * @return bool
     */
    private function allRentabilitiesAreEmpty($data): bool
    {
        $total = array_reduce($data, function ($carry, $fundAdministrator): float {
            $carry += array_reduce($fundAdministrator, function ($carry, $investmentFund): float {
                $carry += $investmentFund;
                return $carry;
            });

            return $carry;
        });

        return empty($total);
    }

    /**
     * Extract investment fund
     *
     * @param string $str
     * @return string
     */
    private function extractInvestmentFund($str): string
    {
        foreach ($this->investmentFunds as $investmentFund) {
            if (stripos($str, "FONDO TIPO {$investmentFund}") !== false) {
                return $investmentFund;
            }
        }

        return '';
    }

    /**
     * Extract fund administrator
     *
     * @param string $str
     * @return string
     */
    private function extractFundAdministrator($str): string
    {
        foreach ($this->fundAdministrators as $fundAdministrator) {
            if (strtoupper($fundAdministrator->name) == $str) {
                return $str;
            }
        }

        return '';
    }
}
