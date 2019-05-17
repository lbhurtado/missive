<?php

namespace LBHurtado\Missive\Tests;

class RoutesTest extends TestCase
{
    /** @test */
    public function relay_sms_can_be_accessed()
    {
        /*** arrange ***/
        $from = '+639171234567'; $to = '+639187654321'; $message = 'Test Messages';
        $attributes = compact('from', 'to', 'message');

        /*** act */
        $crawler = $this->post('api/sms/relay', $attributes);

        /*** assert ***/
        $crawler->assertStatus(200)->assertJson(['data' => $attributes]);
        $this->assertDatabaseHas('s_m_s_s', $attributes);
    }
}
