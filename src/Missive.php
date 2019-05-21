<?php

namespace LBHurtado\Missive;

use LBHurtado\Missive\Classes\SMSAbstract;
use LBHurtado\Missive\Repositories\SMSRepository;

class Missive
{
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
        return $this->sms = $this->smss->create($attributes);
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

     public static function instance()
     {
         return app(Missive::class)->getInstance();
     }

     public function getInstance()
     {
         static $instance = null;

         if (null === $instance) {
             $instance = resolve('missive');
         }

         return $instance;
     }
}
