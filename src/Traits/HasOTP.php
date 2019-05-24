<?php

namespace LBHurtado\Missive\Traits;

use OTPHP\TOTP;

trait HasOTP
{
    /** @var \OTPHP\TOTPInterface */
    protected $totp;

    public function challenge($notification = null)
    {
        $this->totp = TOTP::create(null, 360);

        //TODO: create a notification interface or abstract
        optional($notification, function ($requestOTP) {
            $requestOTP::dispatch($this, $this->totp->now());
        });

        return $this->totp;
    }

    public function verify($otp)
    {
        $verified = $this->totp->verify($otp);

        if ($verified) $this->forceFill(['verified_at' => now()])->save();

        return $this;
    }
}
