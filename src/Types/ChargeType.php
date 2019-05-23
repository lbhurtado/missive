<?php

namespace LBHurtado\Missive\Types;

use Eloquent\Enumeration\AbstractEnumeration;

class ChargeType extends AbstractEnumeration
{
    const INCOMING_SMS = 'incoming-sms';
    const OUTGOING_SMS = 'outgoing-sms';
    const LBS = 'lbs';
    const LOAD_10 = 'load-10';
    const LOAD_25 = 'load-25';
}
