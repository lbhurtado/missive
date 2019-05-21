<?php

namespace LBHurtado\Missive;

use LBHurtado\Missive\Classes\SMSAbstract;

class Missive
{
    /** @var  \LBHurtado\Missive\Classes\SMSAbstract */
    protected $sms;

    public function setSMS(SMSAbstract $sms)
    {
        $this->sms = $sms;

        return $this;
    }

    public function getSMS(): SMSAbstract
    {
        return $this->sms;
    }
}
