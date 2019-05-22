<?php

namespace LBHurtado\Missive\Pivots;

use LBHurtado\Missive\Models\Airtime;
use LBHurtado\Missive\Models\Contact;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AirtimeContact extends Pivot
{
    public function airtime()
    {
        return $this->belongsTo(Airtime::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
