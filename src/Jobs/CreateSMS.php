<?php

namespace LBHurtado\Missive\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use LBHurtado\Missive\Repositories\SMSRepository;

class CreateSMS
{
    use Dispatchable, Queueable;

    protected $attributes;

    public function __construct($attributes)
    {
        $this->attributes = $attributes;
    }

    public function handle(SMSRepository $smss)
    {
        $smss->create($this->attributes);
    }
}
