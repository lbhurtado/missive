<?php

namespace LBHurtado\Missive\Tests;

use Illuminate\Support\Arr;
use LBHurtado\Missive\Models\Contact;
use Illuminate\Database\QueryException;

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
}
