<?php

namespace LBHurtado\Missive\Pivots;

use LBHurtado\Missive\Models\Airtime;
use LBHurtado\Missive\Models\Contact;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AirtimeContact extends Pivot
{
    const DEFAULT_QTY = 2;

    protected $attributes = [
        'qty' => self::DEFAULT_QTY
    ];

    public function airtime(): BelongsTo
    {
        return $this->belongsTo(Airtime::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function setQty(int $qty): AirtimeContact
    {
        $this->qty = $qty;

        return $this;
    }

}
