<?php

namespace LBHurtado\Missive\Jobs;

use Illuminate\Bus\Queueable;
use LBHurtado\Missive\Missive;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use LBHurtado\Missive\Classes\SMSAbstract;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sms;

    public function __construct(SMSAbstract $sms)
    {
        $this->sms = $sms;
    }

    public function handle(Missive $missive)
    {
        $missive->process($this->sms);
    }
}
