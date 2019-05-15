<?php

namespace LBHurtado\Missive\Events;

use Opis\Events\Event;
use LBHurtado\Missive\Classes\MobileHandle;

class ContactEvent extends Event
{
    protected $contact;

    public function setContact(MobileHandle $contact)
    {
        $this->contact = $contact;

        return $this;
    }

    public function getContact()
    {
        return $this->contact;
    }
}