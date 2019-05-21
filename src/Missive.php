<?php

namespace LBHurtado\Missive;

use LBHurtado\Missive\Classes\SMSAbstract;
use LBHurtado\Missive\Repositories\SMSRepository;

class Missive
{
    /** @var LBHurtado\Missive\Missive */
    private static $instance;

    /** @var \LBHurtado\Missive\Repositories\SMSRepository */
    protected $smss;

    /** @var  \LBHurtado\Missive\Classes\SMSAbstract */
    protected $sms;

    public function __construct(SMSRepository $smss)
    {
        $this->smss = $smss;
    }

    public function createSMS($attributes = [])
    {
        $this->sms = $this->smss->create($attributes);
        app()->instance(SMSAbstract::class, $this->sms);

        return $this->sms;
    }

    protected function setSMS(SMSAbstract $sms)
    {
        $this->sms = $sms;

        return $this;
    }

    public function getSMS(): SMSAbstract
    {
        return $this->sms;
    }

     public function getInstance()
     {
         if ( ! isset( self::$instance ) ) {
             self::$instance = app(Missive::class);
         }

         return self::$instance;
     }
}
