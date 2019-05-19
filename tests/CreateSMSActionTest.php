<?php

namespace LBHurtado\Missive\Tests;

use Opis\Events\EventDispatcher;
use Illuminate\Support\Facades\Request;
use LBHurtado\Missive\Actions\CreateSMSAction;
use Joselfonseca\LaravelTactician\CommandBusInterface;

class CreateSMSActionTest extends TestCase
{
    /** @var  \Joselfonseca\LaravelTactician\CommandBusInterface */
    protected $bus;

    /** @var  \Opis\Events\EventDispatcher */
    protected $dispatcher;

    public function setUp(): void
    {
        parent::setUp();

        $this->bus = app(CommandBusInterface::class);
        $this->dispatcher = app(EventDispatcher::class);
    }

    /** @test */
    public function action_ultimately_creates_an_sms_when_invoked()
    {
        /*** arrange ***/
        $from = '+639171234567'; $to = '+639187654321'; $message = 'Test Messages';
        $request = Request::create('/api/sms/relay', 'POST', $attributes = compact('from', 'to', 'message'));

        /*** act */
        $action = new CreateSMSAction($this->bus, $this->dispatcher, $request);
        $action->__invoke();

        /*** assert ***/
        $this->assertEquals($attributes, $action->getData());
        $this->assertDatabaseHas('s_m_s_s', $attributes);
    }
}
