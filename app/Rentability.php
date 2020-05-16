<?php

namespace App;

use App\FundAdministrator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
     * Scope a query to only include users of a given type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Carbon $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfDate($query, Carbon $date): Builder
    {
        return $query->where('date', $date->format('Y-m-01'));
    }
}
