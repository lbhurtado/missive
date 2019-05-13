<?php

namespace LBHurtado\Missive\Actions;

use LBHurtado\Tactician\Classes\ActionAbstract;
use LBHurtado\Tactician\Contracts\ActionInterface;
use LBHurtado\Missive\Events\{SMSEvent, SMSEvents};
use LBHurtado\Missive\{Commands\CreateSMSCommand, Handlers\CreateSMSHandler,
                Responders\CreateSMSResponder, Validators\CreateSMSValidator};
use LBHurtado\Missive\Jobs\CreateContact;
use LBHurtado\Missive\Models\SMS;

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
            \Log::info('CreateSMSAction::setup');
            tap($event->getSMS(), function (SMS $sms) {
                \Log::info($sms);
                $this->dispatchNow(new CreateContact($sms->from));
            });
        });
    }
}
