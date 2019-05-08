<?php

namespace LBHurtado\Missive\Models;

use LBHurtado\Missive\Classes\MobileHandle;

class Relay extends MobileHandle
{   
    protected function getTableIndex(): string
    {
        return 'relays';
    }
}
