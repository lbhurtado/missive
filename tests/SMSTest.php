<?php

namespace LBHurtado\Missive\Tests;

use Illuminate\Support\Arr;
use LBHurtado\Missive\Models\SMS;

class SMSTest extends TestCase
{
    /** @test */
    public function sms_model_has_from_to_messages_fields()
    {
        /*** arrange ***/
        $from = '+639171234567'; $to = '+639187654321'; $message = 'Test Messages';

        /*** act ***/
        $sms = SMS::create($attributes = compact('from', 'to', 'message'));

        /*** assert ***/
        $this->assertEquals($attributes, Arr::only($sms->toArray(), array_keys($attributes)));
    }
}
