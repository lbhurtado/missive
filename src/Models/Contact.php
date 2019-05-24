<?php

namespace LBHurtado\Missive\Models;

use LBHurtado\Missive\Traits\HasOTP;
use LBHurtado\Missive\Traits\HasAirtime;
use LBHurtado\Missive\Classes\MobileHandle;

class Contact extends MobileHandle
{
    use HasAirtime, HasOTP;

    protected $fillable = [
        'mobile',
        'handle',
        'uri',
    ];

    protected function getTableIndex(): string
    {
        return 'contacts';
    }
}
