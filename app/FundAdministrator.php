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
}
