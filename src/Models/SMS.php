<?php

namespace LBHurtado\Missive\Models;

use LBHurtado\Missive\Classes\SMSAbstract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SMS extends SMSAbstract
{
    public function origin(): BelongsTo
    {
        return $this->belongsTo(config('missive.classes.models.contact', Contact::class), 'from', 'mobile');
    }

    public function destination(): BelongsTo
    {
    	return $this->belongsTo(config('missive.classes.models.relay', Relay::class), 'to', 'mobile');
    }
}
