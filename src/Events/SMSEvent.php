<?php

namespace LBHurtado\Missive\Events;

use Opis\Events\Event;
use LBHurtado\Missive\Models\SMS;

class SMSEvent extends Event
{
    protected $sms;

    public function setSMS(SMS $sms)
    {
        $this->sms = $sms;

        \Log::info('SMSEvent::setSMS');
        return $this;
    }

    public function getSMS()
    {
        return $this->sms;
    }
}
