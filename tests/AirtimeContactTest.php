<?php

namespace LBHurtado\Missive\Tests;

use Illuminate\Support\Arr;
use Opis\Events\EventDispatcher;
use Illuminate\Database\QueryException;
use LBHurtado\Missive\Pivots\AirtimeContact;
use LBHurtado\Missive\Models\{Airtime, Contact};
use LBHurtado\Missive\Events\AirtimeContactEvent;
use LBHurtado\Missive\Events\AirtimeContactEvents;

class AirtimeContactTest extends TestCase
{
    protected $airtime;

    protected $contact;

    public function setUp(): void
    {
        parent::setUp();

        $this->airtime = factory(Airtime::class)->create();
        $this->contact = factory(Contact::class)->create();
    }
    /** @test */
    public function airtime_contact_pivot_has_airtime_id_contact_id_qty()
    {
        /*** arrange ***/
        $airtime_id = $this->airtime->id; $contact_id = $this->contact->id; $qty = 1;

        /*** act ***/
        $airtime_contact = AirtimeContact::create($attributes = compact('airtime_id', 'contact_id', 'qty'));

        /*** assert ***/
        $this->assertEquals($attributes, Arr::only($airtime_contact->toArray(), array_keys($attributes)));
    }

    /** @test */
    public function airtime_contact_pivot_airtime_id_is_required()
    {
        /*** arrange ***/
        $airtime_id = null; $contact_id = $this->contact->id; $qty = 1;

        /*** assert ***/
        $this->expectException(QueryException::class);

        /*** act ***/
        AirtimeContact::create($attributes = compact('airtime_id', 'contact_id', 'qty'));
    }

    /** @test */
    public function airtime_contact_pivot_contact_id_is_required()
    {
        /*** arrange ***/
        $airtime_id = $this->airtime->id; $contact_id = null; $qty = 1;

        /*** assert ***/
        $this->expectException(QueryException::class);

        /*** act ***/
        AirtimeContact::create($attributes = compact('airtime_id', 'contact_id', 'qty'));
    }

    /** @test */
    public function airtime_contact_pivot_qty_is_required()
    {
        /*** arrange ***/
        $airtime_id = $this->airtime->id; $contact_id = $this->contact->id; $qty = null;

        /*** assert ***/
        $this->expectException(QueryException::class);

        /*** act ***/
        AirtimeContact::create($attributes = compact('airtime_id', 'contact_id', 'qty'));
    }

    /** @test */
    public function airtime_contact_pivot_qty_has_a_default()
    {
        /*** arrange ***/
        $airtime_id = $this->airtime->id; $contact_id = $this->contact->id; $qty = 1;
        $attributes = compact('airtime_id', 'contact_id', 'qty');

            /*** act ***/
        $airtime_contact = AirtimeContact::create(compact('airtime_id', 'contact_id'));

        /*** assert ***/
        $this->assertEquals($attributes, Arr::only($airtime_contact->toArray(), array_keys($attributes)));
    }


    /** @test */
    public function airtime_contact_creation_has_event()
    {
        /*** arrange ***/
        $airtime_id = $this->airtime->id; $contact_id = $this->contact->id;
        $dispatcher = app(EventDispatcher::class);

        /*** assert ***/
        $dispatcher->handle(AirtimeContactEvents::CREATED, function (AirtimeContactEvent $event) use ($airtime_id, $contact_id) {
            $this->assertEquals($airtime_id, $event->getAirtimeContact()->airtime->id);
            $this->assertEquals($contact_id, $event->getAirtimeContact()->contact->id);
        });

        /*** act ***/
        AirtimeContact::create($attributes = compact('airtime_id', 'contact_id'));
    }

    /** @test */
    public function contact_can_add_airtime_as_airtime_contact_pivot()
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
