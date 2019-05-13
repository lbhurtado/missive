<?php

namespace LBHurtado\Missive;

use LBHurtado\Missive\Models\SMS;

class Missive
{
    protected $sms;

    public function setSMS(SMS $sms)
    {
        $this->sms = $sms;

        return $this;
    }

    public function getSMS()
    {
        return $this->sms;
    }
}
