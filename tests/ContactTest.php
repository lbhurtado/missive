<?php

namespace LBHurtado\Missive\Tests;

use Illuminate\Support\Arr;
use Opis\Events\EventDispatcher;
use LBHurtado\Missive\Models\Contact;
use Illuminate\Database\QueryException;
use LBHurtado\Missive\Events\ContactEvent;
use LBHurtado\Missive\Events\ContactEvents;
use LBHurtado\Missive\Classes\MobileHandle;

class ContactTest extends TestCase
{
    /** @test */
    public function contact_model_has_mobile_handle_fields()
    {
        /*** arrange ***/
        $mobile = '+639171234567'; $handle = 'John Doe';

        /*** act ***/
        $contact = Contact::create($attributes = compact('mobile', 'handle'));

        /*** assert ***/
        $this->assertEquals($attributes, Arr::only($contact->toArray(), array_keys($attributes)));
    }

    /** @test */
    public function contact_handle_field_is_not_required()
    {
        /*** arrange ***/
        $mobile = '+639171234567';

        /*** act ***/
        $contact = Contact::create($attributes = compact('mobile'));

        /*** assert ***/
        $this->assertNull($contact->handle);
    }

    /** @test */
    public function contact_mobile_field_is_required()
    {
        /*** arrange ***/
        $mobile = null;

        /*** assert ***/
        $this->expectException(QueryException::class);

        /*** act ***/
        Contact::create($attributes = compact('mobile'));
    }

    /** @test */
    public function contact_model_has_URI_extra_attribute_field()
    {
        /*** arrange ***/
        $contact = factory(Contact::class)->create();
        $provisioningURI = 'otpauth://totp/alice%40google.com?secret=JBSWY3DPEHPK3PXP&foo=bar';

        /*** act ***/
        $contact->URI = $provisioningURI;

        /*** assert ***/
        $this->assertEquals($provisioningURI, $contact->extra_attributes['uri']);
    }

    /** @test */
    public function contact_has_model_factory()
    {
        /*** arrange ***/
        $mobile = '+639171234567';

        /*** act ***/
        $contact = factory(Contact::class)->create($attributes = compact('mobile'));

        /*** assert ***/
        $this->assertEquals($contact->id, Contact::where($attributes)->first()->id);
    }

    /** @test */
    public function contact_is_mobile_handle()
    {
        /*** act ***/
        $contact = factory(Contact::class)->create();

        /*** assert ***/
        $this->assertInstanceOf(MobileHandle::class, $contact);
    }

    /** @test */
    public function contact_creation_has_event()
    {
        /*** arrange ***/
        $mobile = '+639171234567';
        $dispatcher = app(EventDispatcher::class);

        /*** assert ***/
        $dispatcher->handle(ContactEvents::CREATED, function (ContactEvent $event) use ($mobile) {
            $this->assertEquals($mobile, $event->getContact()->mobile);
        });

        /*** act ***/
        factory(Contact::class)->create(compact('mobile'));
    }

    /** @test */
    public function contact_can_challenge_and_verify_otp()
    {
        /*** arrange ***/
        $contact = factory(Contact::class)->create();

        /*** act ***/
        $otp = $contact->challenge()->getTOTP()->now();

        /*** assert ***/
        $this->assertTrue($contact->verify($otp) == true);
        $this->assertTrue($contact->verified());
    }
}
