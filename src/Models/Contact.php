<?php

namespace LBHurtado\Missive\Models;

use LBHurtado\Missive\Classes\MobileHandle;

class Contact extends MobileHandle
{   
    protected function getTableIndex(): string
    {
        return 'contacts';
    }
}
