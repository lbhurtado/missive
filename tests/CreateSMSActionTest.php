<?php

namespace LBHurtado\Missive\Tests;

use Illuminate\Support\Facades\Request;
use Opis\Events\EventDispatcher;
use LBHurtado\Missive\Actions\CreateSMSAction;
use Joselfonseca\LaravelTactician\CommandBusInterface;

class CreateSMSActionTest extends TestCase
{
    /** @test */
    public function xxx()
    {
        /*** arrange ***/
        $bus = app(CommandBusInterface::class);
        $dispatcher = app(EventDispatcher::class);
        $from = '+639171234567'; $to = '+639187654321'; $message = 'Test Messages';
        $attributes = compact('from', 'to', 'message');
        $request = Request::create('/api/sms/relay', 'POST', $attributes);

        /*** act */
        $action = new CreateSMSAction($bus, $dispatcher, $request);
        $action->__invoke();

        /*** assert ***/
        $this->assertEquals($attributes, $action->getData());
        $this->assertDatabaseHas('s_m_s_s', $attributes);
    }
}
