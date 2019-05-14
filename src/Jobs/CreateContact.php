<?php

namespace LBHurtado\Missive\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use LBHurtado\Missive\Repositories\ContactRepository;

class CreateContact
{
    use Dispatchable, Queueable;

    protected $mobile;

    protected $handle;

    public function __construct($mobile, $handle = null)
    {
        $this->mobile = $mobile;
        $this->handle = $handle;
    }

    public function handle(ContactRepository $contacts)
    {
        $contacts->updateOrCreate(['mobile' => $this->mobile], ['handle' => $this->handle]);
    }
}
