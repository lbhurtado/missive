<?php

namespace LBHurtado\Missive\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use LBHurtado\Missive\Repositories\RelayRepository;

class CreateRelay
{
    use Dispatchable, Queueable;

    protected $mobile;

    protected $handle;

    public function __construct($mobile, $handle = null)
    {
        $this->mobile = $mobile;
        $this->handle = $handle;
    }

    public function handle(RelayRepository $relays)
    {
        $relays->updateOrCreate(['mobile' => $this->mobile], ['handle' => $this->handle]);
    }
}
