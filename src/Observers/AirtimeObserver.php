<?php

namespace LBHurtado\Missive\Observers;

use Opis\Events\EventDispatcher;
use LBHurtado\Missive\Models\Airtime;
use LBHurtado\Missive\Events\{AirtimeEvent, AirtimeEvents};

class AirtimeObserver
{
    protected $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function created(Airtime $airtime)
    {
        tap($this->dispatcher, function($dispatcher) use ($airtime) {
            tap(new AirtimeEvent(AirtimeEvents::CREATED), function (AirtimeEvent $event) use ($airtime, $dispatcher) {
                $dispatcher->dispatch($event->setAirtime($airtime));
            });
        });
    }
}