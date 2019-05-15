<?php

namespace LBHurtado\Missive\Observers;

use Opis\Events\EventDispatcher;
use LBHurtado\Missive\Classes\MobileHandle;
use LBHurtado\Missive\Events\{RelayEvent, RelayEvents};

class RelayObserver
{
    protected $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function created(MobileHandle $relay)
    {
        tap($this->dispatcher, function($dispatcher) use ($relay) {
            tap(new RelayEvent(RelayEvents::CREATED), function (RelayEvent $event) use ($relay, $dispatcher) {
                $dispatcher->dispatch($event->setRelay($relay));
            });
        });
    }
}