<?php

namespace LBHurtado\Missive\Actions;

use LBHurtado\Tactician\Contracts\ActionInterface;
use LBHurtado\Missive\Validators\CreateSMSValidator;
use LBHurtado\Missive\Responders\CreateSMSResponder;
use LBHurtado\Missive\Actions\Middleware\TopupMobileHandler;

class TopupMobileAction extends CreateSMSAction implements ActionInterface
{
    protected $middlewares = [
        CreateSMSValidator::class,
        TopupMobileHandler::class,
        CreateSMSResponder::class,
    ];

    public function getMiddlewares():array
    {
        return config('missive.classes.middlewares.sms.topup', $this->middlewares);
    }
}
