<?php

namespace LBHurtado\Missive\Tests;

use LBHurtado\Missive\Models\SMS;
use LBHurtado\Missive\Repositories\SMSRepository;

class SMSTest extends TestCase
{
    /** @test */
    public function sms_model_has_from_to_messages_fields()
    {
        $from = '+639171234567'; $to = '+639187654321'; $message = 'Test Messages';
        $sms = SMS::create(compact('from', 'to', 'message'));

        $this->assertEquals($from, $sms->from);
        $this->assertEquals($to, $sms->to);
        $this->assertEquals($message, $sms->message);
    }

    /** @test */
    public function sms_repository_can_create_sms()
    {
        $from = '+639171234567'; $to = '+639187654321'; $message = 'Test Messages';
        app(SMSRepository::class)->create(compact('from', 'to', 'message'));

        $this->assertDatabaseHas(config('missive.table_names.smss'), compact('from', 'to', 'message'));
    }
}
