<?php

namespace LBHurtado\Missive\Models;

use LBHurtado\Missive\Classes\SMSAbstract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SMS extends SMSAbstract
{
    public function origin(): BelongsTo
    {
        return $this->belongsTo(get_class(app('missive.contact')), 'from', 'mobile');
    }

    public function destination(): BelongsTo
    {
    	return $this->belongsTo(get_class(app('missive.relay')), 'to', 'mobile');
    }
}
