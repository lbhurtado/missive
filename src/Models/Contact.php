<?php

namespace LBHurtado\Missive\Models;

use LBHurtado\Missive\Traits\HasAirtime;
use LBHurtado\Missive\Classes\MobileHandle;

class Contact extends MobileHandle
{
    use HasAirtime;

    protected function getTableIndex(): string
    {
        return 'contacts';
    }
}
