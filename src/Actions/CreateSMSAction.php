<?php

namespace LBHurtado\Missive\Actions;

use LBHurtado\Tactician\Classes\ActionAbstract;
use LBHurtado\Tactician\Contracts\ActionInterface;
use LBHurtado\Missive\{Commands\CreateSMSCommand,
    Handlers\CreateSMSHandler, Responders\CreateSMSResponder, Validators\CreateSMSValidator};

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
        // TODO: Implement setup() method.
    }
}
