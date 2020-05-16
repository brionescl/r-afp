<?php

namespace App;

use App\Rentability;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FundAdministrator extends Model
{
    /**
     * Get the rentabilities
     *
     * @return HasMany|Collection|Rentability[]
     */
    public function rentabilities(): HasMany
    {
        return $this->hasMany(Rentability::class);
    }

    /**
     * Get rentabilities of funds by date
     *
     * @param Carbon $date
     * @return Collection|Rentability[]
     */
    public function getRentabilitiesOfFundsByDate(Carbon $date): Collection
    {
        $rentabilities = Rentability::where('fund_administrator_id', $this->id)
            ->where('date', $date->isoFormat('YYYY-MM-01'))
            ->get();

        return $rentabilities;
    }
}
