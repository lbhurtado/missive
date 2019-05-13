<?php

namespace LBHurtado\Missive\Observers;

use Opis\Events\EventDispatcher;
use LBHurtado\Missive\Models\SMS;
use LBHurtado\Missive\Events\{SMSEvent, SMSEvents};

class SMSObserver
{
    protected $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function created(SMS $sms)
    {
        \Log::info('SMSObserver::created');
        tap($this->dispatcher, function($dispatcher) use ($sms) {
            tap(new SMSEvent(SMSEvents::CREATED), function (SMSEvent $event) use ($sms, $dispatcher) {
                \Log::info('SMSObserver::created::tap(new SMSEvent');
                $dispatcher->dispatch($event->setSMS($sms));
            });
        });
    }
}
