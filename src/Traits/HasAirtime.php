<?php

namespace LBHurtado\Missive\Traits;

use LBHurtado\Missive\Models\Airtime;
use LBHurtado\Missive\Pivots\AirtimeContact as Pivot;

trait HasAirtime
{
    public function airtimes()
    {
        return $this->belongsToMany(Airtime::class)
            ->withPivot('qty')
            ->using(Pivot::class)
            ->withTimestamps();
    }

    public function addAirtime(Airtime $airtime, Pivot $pivot)
    {
        return $this->airtimes()->attach($airtime, $pivot->getAttributes());
    }

    public function updateAirtime(Airtime $airtime, Pivot $pivot)
    {
        return $this->airtimes()->updateExistingPivot($airtime, $pivot->getAttributes());
    }
}
