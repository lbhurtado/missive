<?php

namespace LBHurtado\Missive\Observers;

use Opis\Events\EventDispatcher;
use LBHurtado\Missive\Pivots\AirtimeContact;
use LBHurtado\Missive\Events\{AirtimeContactEvent, AirtimeContactEvents};

class AirtimeContactObserver
{
    protected $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function created(AirtimeContact $airtime_contact)
    {
        tap($this->dispatcher, function($dispatcher) use ($airtime_contact) {
            tap(new AirtimeContactEvent(AirtimeContactEvents::CREATED), function (AirtimeContactEvent $event) use ($airtime_contact, $dispatcher) {
                $dispatcher->dispatch($event->setAirtimeContact($airtime_contact));
            });
        });
    }
}
