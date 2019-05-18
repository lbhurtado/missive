<?php

namespace LBHurtado\Missive\Tests;

use Illuminate\Support\Arr;
use Opis\Events\EventDispatcher;
use LBHurtado\Missive\Models\Relay;
use Illuminate\Database\QueryException;
use LBHurtado\Missive\Events\RelayEvent;
use LBHurtado\Missive\Events\RelayEvents;
use LBHurtado\Missive\Classes\MobileHandle;

class RelayTest extends TestCase
{
    /** @test */
    public function relay_model_has_mobile_handle_fields()
    {
        /*** arrange ***/
        $mobile = '+639171234567'; $handle = 'John Doe';

        /*** act ***/
        $relay = Relay::create($attributes = compact('mobile', 'handle'));

        /*** assert ***/
        $this->assertEquals($attributes, Arr::only($relay->toArray(), array_keys($attributes)));
    }

    /** @test */
    public function relay_handle_field_is_not_required()
    {
        /*** arrange ***/
        $mobile = '+639171234567';

        /*** act ***/
        $relay = Relay::create($attributes = compact('mobile'));

        /*** assert ***/
        $this->assertNull($relay->handle);
    }

    /** @test */
    public function relay_mobile_field_is_required()
    {
        /*** arrange ***/
        $mobile = null;

        /*** assert ***/
        $this->expectException(QueryException::class);

        /*** act ***/
        Relay::create($attributes = compact('mobile'));
    }

    /** @test */
    public function relay_has_model_factory()
    {
        /*** arrange ***/
        $mobile = '+639171234567';

        /*** act ***/
        $relay = factory(Relay::class)->create($attributes = compact('mobile'));

        /*** assert ***/
        $this->assertEquals($relay->id, Relay::where($attributes)->first()->id);
    }

    /** @test */
    public function relay_is_mobile_handle()
    {
        /*** act ***/
        $relay = factory(Relay::class)->create();

        /*** assert ***/
        $this->assertInstanceOf(MobileHandle::class, $relay);
    }

    /** @test */
    public function relay_creation_has_event()
    {
        $dispatcher = app(EventDispatcher::class);

        /*** assert ***/
        $dispatcher->handle(RelayEvents::CREATED, function (RelayEvent $event) {
            $this->assertTrue(true);
        });

        /*** act ***/
        factory(Relay::class)->create();
    }
}
