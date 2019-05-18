<?php

namespace LBHurtado\Missive\Tests;

use Illuminate\Support\Arr;
use LBHurtado\Missive\Models\SMS;
use LBHurtado\Missive\Models\Relay;
use LBHurtado\Missive\Models\Contact;
use Illuminate\Database\QueryException;

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

    /** @test */
    public function sms_from_field_is_required()
    {
        /*** arrange ***/
        $from = null; $to = '+639187654321'; $message = 'Test Messages';

        /*** assert ***/
        $this->expectException(QueryException::class);

        /*** act ***/
        SMS::create($attributes = compact('from', 'to', 'message'));
    }

    /** @test */
    public function sms_to_field_is_required()
    {
        /*** arrange ***/
        $from = '+639171234567'; $to = null; $message = 'Test Messages';

        /*** assert ***/
        $this->expectException(QueryException::class);

        /*** act ***/
        SMS::create($attributes = compact('from', 'to', 'message'));
    }

    /** @test */
    public function sms_message_field_is_not_required()
    {
        /*** arrange ***/
        $from = '+639171234567'; $to = '+639187654321'; $message = null;

        /*** act ***/
        $sms = SMS::create($attributes = compact('from', 'to', 'message'));

        /*** assert ***/
        $this->assertNull($sms->message);
    }

    /** @test */
    public function sms_has_model_factory()
    {
        /*** arrange ***/
        $from = '+639171234567';

        /*** act ***/
        $sms = factory(SMS::class)->create($attributes = compact('from'));

        /*** assert ***/
        $this->assertEquals($sms->id, SMS::where($attributes)->first()->id);
    }

    /** @test */
    public function sms_has_origin_relation_as_contact()
    {
        /*** arrange ***/
        $mobile = $from = '+639171234567';

        /*** act ***/
        $sms = factory(SMS::class)->create($attributes = compact('from'));
        $contact = factory(Contact::class)->create($attributes = compact('mobile'));

        /*** assert ***/
        $this->assertTrue($sms->origin->is($contact));
    }

    /** @test */
    public function sms_has_destination_relation_as_relay()
    {
        /*** arrange ***/
        $mobile = $to = '+639171234567';

        /*** act ***/
        $sms = factory(SMS::class)->create($attributes = compact('to'));
        $relay = factory(Relay::class)->create($attributes = compact('mobile'));

        /*** assert ***/
        $this->assertTrue($sms->destination->is($relay));
    }
}
