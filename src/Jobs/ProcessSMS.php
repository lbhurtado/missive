<?php

namespace LBHurtado\Missive\Jobs;

use Illuminate\Bus\Queueable;
use LBHurtado\Missive\Routing\Router;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use LBHurtado\Missive\Classes\SMSAbstract;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var SMSAbstract
     */
    protected $sms;

    /**
     * ProcessSMS constructor.
     * @param SMSAbstract $sms
     */
    public function __construct(SMSAbstract $sms)
    {
        $this->sms = $sms;
    }

    /**
     * @param Router $router
     */
    public function handle(Router $router)
    {
        $router->process($this->sms);
    }
}
