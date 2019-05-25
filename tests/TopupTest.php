<?php

namespace LBHurtado\Missive\Tests;

use Illuminate\Support\Arr;
use LBHurtado\Missive\Models\Topup;
use LBHurtado\Missive\Models\Contact;
use Opis\Events\EventDispatcher;
use LBHurtado\Missive\Models\Relay;
use Illuminate\Database\QueryException;
use LBHurtado\Missive\Events\RelayEvent;
use LBHurtado\Missive\Events\RelayEvents;
use LBHurtado\Missive\Classes\MobileHandle;

class TopupTest extends TestCase
{
    /** @test */
    public function topup_model_has_contact_id_amount_fields()
    {
        /*** arrange ***/
        $mobile = '+639171234567'; $amount = 15;
        $contact = factory(Contact::class)->create(compact('mobile'));

        /*** act ***/
        $topup = Topup::make(compact( 'amount'))->contact()->associate($contact);

        /*** assert ***/
        $this->assertEquals($contact, $topup->contact);
        $this->assertEquals($amount, $topup->amount);
    }

    /** @test */
    public function topup_contact_id_field_is_required()
    {
        /*** arrange ***/
        $amount = 15;

        /*** assert ***/
        $this->expectException(QueryException::class);

        /*** act ***/
        Topup::create(compact( 'amount'));
    }
//
//    /** @test */
//    public function relay_has_model_factory()
//    {
//        /*** arrange ***/
//        $mobile = '+639171234567';
//
//        /*** act ***/
//        $relay = factory(Relay::class)->create($attributes = compact('mobile'));
//
//        /*** assert ***/
//        $this->assertEquals($relay->id, Relay::where($attributes)->first()->id);
//    }
//
//    /** @test */
//    public function relay_is_mobile_handle()
//    {
//        /*** act ***/
//        $relay = factory(Relay::class)->create();
//
//        /*** assert ***/
//        $this->assertInstanceOf(MobileHandle::class, $relay);
//    }
//
//    /** @test */
//    public function relay_creation_has_event()
//    {
//        /*** arrange ***/
//        $mobile = '+639171234567';
//        $dispatcher = app(EventDispatcher::class);
//
//        /*** assert ***/
//        $dispatcher->handle(RelayEvents::CREATED, function (RelayEvent $event) use ($mobile) {
//            $this->assertEquals($mobile, $event->getRelay()->mobile);
//        });
//
//        /*** act ***/
//        factory(Relay::class)->create(compact('mobile'));
//    }
}
