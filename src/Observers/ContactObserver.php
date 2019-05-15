<?php

namespace LBHurtado\Missive\Observers;

use Opis\Events\EventDispatcher;
use LBHurtado\Missive\Classes\MobileHandle;
use LBHurtado\Missive\Events\{ContactEvent, ContactEvents};

class ContactObserver
{
    protected $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function created(MobileHandle $contact)
    {
        tap($this->dispatcher, function($dispatcher) use ($contact) {
            tap(new ContactEvent(ContactEvents::CREATED), function (ContactEvent $event) use ($contact, $dispatcher) {
                $dispatcher->dispatch($event->setContact($contact));
            });
        });
    }
}