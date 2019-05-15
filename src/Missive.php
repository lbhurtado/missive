<?php

namespace LBHurtado\Missive;

use LBHurtado\Missive\Classes\SMSAbstract;
use LBHurtado\Missive\Repositories\SMSRepository;

class Missive
{
    protected $smss;

    protected $sms;

    public function __construct(SMSRepository $smss)
    {
        $this->smss = $smss;
    }

    public function createSMS($attributes = [])
    {
        $this->sms = $this->smss->create($attributes);
    }

    public function getSMS(): SMSAbstract
    {
        return $this->sms;
    }
}
