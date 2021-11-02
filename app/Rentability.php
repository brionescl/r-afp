<?php

namespace App;

use App\FundAdministrator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rentability extends Model
{
    protected $fillable = [
        'fund_administrator_id',
        'investment_fund',
        'date'
    ];

    /**
     * Get the fund administrator
     *
     * @return BelongsTo|FundAdministrator
     */
    public function fundAdministrator(): BelongsTo
    {
        return $this->belongsTo(FundAdministrator::class);
    }

    /**
     * Scope a query by specific month.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Carbon $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfDate($query, Carbon $date): Builder
    {
        return $query->where('date', $date->format('Y-m-01'));
    }

    /**
     * Scope a query by last 12 months.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Carbon $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfLast12Months($query, Carbon $date): Builder
    {
        return $query->whereBetween('date', [
            $date->copy()->subMonths(12)->startOfMonth()->format('Y-m-d'),
            $date->endOfMonth()->format('Y-m-d')
        ]);
    }

    public function groupByFundAdministrator(Builder $scope): Collection
    {
        return $scope
            ->orderBy('fund_administrator_id', 'asc')
            ->orderBy('investment_fund', 'asc')
            ->get()
            ->groupBy(function ($item) {
                return $item->fundAdministrator->name;
            });
    }
}
