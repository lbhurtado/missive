<?php

namespace LBHurtado\Missive\Tests;

use Illuminate\Support\Arr;
use Opis\Events\EventDispatcher;
use Illuminate\Database\QueryException;
use LBHurtado\Missive\Events\AirtimeEvent;
use LBHurtado\Missive\Events\AirtimeEvents;
use LBHurtado\Missive\Pivots\AirtimeContact;
use LBHurtado\Missive\Models\{Airtime, Contact};

class AirtimeTest extends TestCase
{
    /** @test */
    public function airtime_model_has_key_credits_fields()
    {
        /*** arrange ***/
        $key = 'incoming-sms'; $credits = '0.01';

        /*** act ***/
        $airtime = Airtime::create($attributes = compact('key', 'credits'));

        /*** assert ***/
        $this->assertEquals($attributes, Arr::only($airtime->toArray(), array_keys($attributes)));
    }

    /** @test */
    public function airtime_key_field_is_required()
    {
        /*** arrange ***/
        $key = null; $credits = '0.01';

        /*** assert ***/
        $this->expectException(QueryException::class);

        /*** act ***/
        Airtime::create(compact('key', 'credits'));
    }

    /** @test */
    public function airtime_key_credits_is_required()
    {
        /*** arrange ***/
        $key = 'incoming-sms'; $credits = null;

        /*** assert ***/
        $this->expectException(QueryException::class);

        /*** act ***/
        Airtime::create(compact('key', 'credits'));
    }

    /** @test */
    public function airtime_has_model_factory()
    {
        /*** arrange ***/
        $key = 'incoming-sms';

        /*** act ***/
        $airtime = factory(Airtime::class)->create($attributes = compact('key'));

        /*** assert ***/
        $this->assertEquals($airtime->id, Airtime::where($attributes)->first()->id);
    }

    /** @test */
    public function airtime_creation_has_event()
    {
        /*** arrange ***/
        $key = 'incoming-sms';
        $dispatcher = app(EventDispatcher::class);

        /*** assert ***/
        $dispatcher->handle(AirtimeEvents::CREATED, function (AirtimeEvent $event) use ($key) {
            $this->assertEquals($key, $event->getAirtime()->key);
        });

        /*** act ***/
        factory(Airtime::class)->create(compact('key'));
    }

    //TODO: put this on a separate test, may in airtime_contac test or in contact test
    /** @test */
    public function contact_has_airtime_contact_as_pivot_for_airtimes()
    {
        /*** arrange ***/
        $contact = factory(Contact::class)->create();
        $airtime = factory(Airtime::class)->create(); $qty = 2;

        /*** act ***/
        $pivot = AirtimeContact::make(compact('qty'));
        $contact->addAirtime($airtime, $pivot);

        /*** assert ***/
        $this->assertTrue($contact->airtimes()->first()->is($airtime));
        $this->assertEquals($qty, $contact->airtimes()->first()->pivot->qty);
    }
}
