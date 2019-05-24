<?php

namespace LBHurtado\Missive\Tests;

use LBHurtado\Missive\Models\Contact;

class RoutesTest extends TestCase
{
    /** @test */
    public function sms_relay_route_can_be_accessed()
    {
        /*** arrange ***/
        $from = '+639171234567'; $to = '+639187654321'; $message = 'Test Messages';
        $attributes = compact('from', 'to', 'message');

        /*** act */
        $crawler = $this->post('api/sms/relay', $attributes);

        /*** assert ***/
        $crawler->assertStatus(200)->assertJson(['data' => $attributes]);
        $this->assertDatabaseHas('s_m_s_s', $attributes);
        $this->assertDatabaseHas('contacts', ['mobile' => $from]);
        $this->assertDatabaseHas('relays', ['mobile' => $to]);
    }

    /** @test */
    public function otp_verification_route_can_be_accessed()
    {
        /*** arrange ***/
        $mobile = $from = '+639171234567'; $to = '+639187654321';
        $contact = factory(Contact::class)->create(compact('mobile'));
        $message = $otp = $contact->challenge()->getTOTP()->now();
        $attributes = compact('from', 'to', 'message');

        /*** act */
        $crawler = $this->post('api/sms/verify', $attributes);

        /*** assert ***/
        $crawler->assertStatus(200)->assertJson(['data' => $attributes]);
        $this->assertDatabaseHas('contacts', ['mobile' => $from]);
        $this->assertTrue(Contact::where('mobile', $from)->first()->verified());
    }
}
