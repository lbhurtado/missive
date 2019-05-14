<?php

namespace LBHurtado\Missive;

use LBHurtado\Missive\Models\SMS;
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

    public function getSMS(): SMS
    {
        return $this->sms;
    }
}
