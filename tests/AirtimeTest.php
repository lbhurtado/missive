<?php

namespace LBHurtado\Missive\Tests;

use Illuminate\Support\Arr;
use Opis\Events\EventDispatcher;
use LBHurtado\Missive\Models\Airtime;
use Illuminate\Database\QueryException;
use LBHurtado\Missive\Events\AirtimeEvent;
use LBHurtado\Missive\Events\AirtimeEvents;
use LBHurtado\Missive\Classes\MobileHandle;

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
        $key = 'incoming-sms'; $credits = '0.01';
        $dispatcher = app(EventDispatcher::class);

        /*** assert ***/
        $dispatcher->handle(AirtimeEvents::CREATED, function (AirtimeEvent $event) use ($key) {
            $this->assertEquals($key, $event->getAirtime()->key);
        });

        /*** act ***/
        factory(Airtime::class)->create(compact('key'));
    }
}