<?php

namespace LBHurtado\Missive\Events;

use Opis\Events\Event;
use LBHurtado\Missive\Pivots\AirtimeContact;

class AirtimeContactEvent extends Event
{
    protected $airtime_contact;

    public function setAirtimeContact(AirtimeContact $airtime_contact)
    {
        $this->airtime_contact = $airtime_contact;

        return $this;
    }

    public function getAirtimeContact()
    {
        return $this->airtime_contact;
    }
}
