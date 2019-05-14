<?php

namespace LBHurtado\Missive\Actions;

use LBHurtado\Missive\Models\SMS;
use LBHurtado\Missive\Jobs\CreateContact;
use LBHurtado\Tactician\Classes\ActionAbstract;
use LBHurtado\Tactician\Contracts\ActionInterface;
use LBHurtado\Missive\Events\{SMSEvent, SMSEvents};
use LBHurtado\Missive\{Commands\CreateSMSCommand, Handlers\CreateSMSHandler,
                Responders\CreateSMSResponder, Validators\CreateSMSValidator};

class CreateSMSAction extends ActionAbstract implements ActionInterface
{
    protected $fields = ['from', 'to', 'message'];

    protected $command = CreateSMSCommand::class;

    protected $handler = CreateSMSHandler::class;

    protected $middlewares = [
        CreateSMSValidator::class,
        CreateSMSResponder::class,
    ];

    public function setup()
    {
        $this->getDispatcher()->handle(SMSEvents::CREATED, function (SMSEvent $event) {
            tap($event->getSMS(), function (SMS $sms) {
                $this->dispatchNow(new CreateContact($sms->from));
            });
        });
    }
}
