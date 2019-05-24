<?php

namespace LBHurtado\Missive\Traits;

use OTPHP\TOTP;
use OTPHP\Factory;
use OTPHP\TOTPInterface;

trait HasOTP
{
    public function setTOTP($totp)
    {
        $totp->setLabel('lester@hurtado.ph');
        $this->uri = $totp->getProvisioningUri();
        $this->save();

        return $this;
    }

    public function getTOTP()
    {
        return Factory::loadFromProvisioningUri($this->uri);
    }

    public function challenge($notification = null)
    {
        tap(TOTP::create(null, 360), function ($totp) {
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
