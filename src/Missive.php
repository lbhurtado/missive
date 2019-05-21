<?php

namespace LBHurtado\Missive;

use LBHurtado\Missive\Classes\SMSAbstract;

class Missive
{
    /** @var  \LBHurtado\Missive\Classes\SMSAbstract */
    protected $sms;

    /**
     * @param SMSAbstract $sms
     * @return $this
     */
    public function setSMS(SMSAbstract $sms)
    {
        $this->sms = $sms;

        return $this;
    }

    /**
     * @return SMSAbstract
     */
    public function getSMS(): SMSAbstract
    {
        return $this->sms;
    }
}
