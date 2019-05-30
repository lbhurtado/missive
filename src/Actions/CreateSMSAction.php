<?php

namespace LBHurtado\Missive\Actions;

use LBHurtado\Missive\Facades\Missive;
use LBHurtado\Missive\Classes\SMSAbstract;
use LBHurtado\Tactician\Classes\ActionAbstract;
use LBHurtado\Tactician\Contracts\ActionInterface;
use LBHurtado\Missive\Events\{SMSEvent, SMSEvents};
use LBHurtado\Missive\Jobs\{CreateContact, CreateRelay, ProcessSMS};
use LBHurtado\Missive\{Commands\CreateSMSCommand, Handlers\CreateSMSHandler,
                Responders\CreateSMSResponder, Validators\CreateSMSValidator};

class CreateSMSAction extends ActionAbstract implements ActionInterface
{
    protected $command = CreateSMSCommand::class;

    protected $handler = CreateSMSHandler::class;

    protected $middlewares = [
        CreateSMSValidator::class,
        CreateSMSResponder::class,
    ];

    public function setup()
    {
        $this->getDispatcher()->handle(SMSEvents::CREATED, function (SMSEvent $event) {
            tap($event->getSMS(), function (SMSAbstract $sms) {
                $this->dispatch(new CreateContact($sms->from));
                $this->dispatch(new CreateRelay($sms->to));
                $this->dispatch(new ProcessSMS($sms));
            });
        });
    }

    public function getCommand(): string
    {
        return config('missive.classes.commands.sms.create', $this->command);
    }

    public function getHandler(): string
    {
        return config('missive.classes.handlers.sms.create', $this->handler);
    }

    public function getMiddlewares(): array
    {
        return config('missive.classes.middlewares.sms.relay', $this->middlewares);
    }

    /**
     * Extract the values in the associative array in config('missive.relay').
     *
     * The following array array
     *
     *  [
     *      'from' => 'from_number',
     *      'to' => 'to_number',
     *      'message' => 'content',
     *  ]
     *
     * becomes
     *
     *  [
     *      'from_number',
     *      'to_number',
     *      'content',
     *  ]
     *
     * which is needed as properties of the command required by the tactician package
     *
     * @return array
     */
    public function getFields(): array
    {
        return optional(Missive::getRelayProviderConfig(), function ($mapping) {
            return array_keys(array_flip($mapping));
        });
    }
}
