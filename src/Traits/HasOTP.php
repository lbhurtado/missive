<?php

namespace LBHurtado\Missive\Traits;

use OTPHP\TOTP;
use OTPHP\Factory;
use OTPHP\TOTPInterface;

trait HasOTP
{
    public function setTOTP(TOTPInterface $totp)
    {
        $totp->setLabel('lester@hurtado.ph');
        $this->URI = $totp->getProvisioningUri();
        $this->save();

        return $this;
    }

    public function getTOTP(): TOTPInterface
    {
        return Factory::loadFromProvisioningUri($this->URI);
    }

    public function challenge($notification = null)
    {
        $period = config('missive.otp.period', 10 * 60); //10 minutes
        tap(TOTP::create(null, $period), function ($totp) {
            $this->setTOTP($totp);
        });

        return $this;
    }

    public function verify($otp)
    {
        $verified = $this->getTOTP()->verify($otp);

        if ($verified) $this->forceFill(['verified_at' => now()])->save();

        return $this;
    }

    public function verified()
    {
        return $this->verified_at && $this->verified_at <= now();
    }
}
