<?php

namespace LBHurtado\Missive\Tests;

use Mockery;
use Illuminate\Support\Arr;
use LBHurtado\Missive\Missive;
use LBHurtado\Missive\Models\SMS;
use LBHurtado\Missive\Handlers\CreateSMSHandler;
use LBHurtado\Missive\Commands\CreateSMSCommand;

class MissiveTest extends TestCase
{
    /** @var Mockery\Mock */
    protected $missive;

    /** @var LBHurtado\Missive\Handlers\CreateSMSHandler */
    protected $handler;

    /** @var LBHurtado\Missive\Commands\CreateSMSCommand */
    protected $command;

    public function setUp(): void
    {
        parent::setUp();

        $this->missive = Mockery::mock(Missive::class);
        $this->handler = new CreateSMSHandler($this->missive);
    }

    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    /** @test */
    public function missive_can_create_sms()
    {
        /*** arrange ***/
        $from = '+639171234567'; $to = '+639187654321'; $message = 'Test Messages';
        $attributes = compact('from', 'to', 'message');
        $this->command = new CreateSMSCommand($attributes);

        $this->missive
            ->shouldReceive('createSMS')
            ->once()
            ->andReturn($sms = factory(SMS::class)->create($attributes));

        /*** act ***/
        $this->handler->handle($this->command);

        /*** assert ***/
        $this->assertEquals($attributes, Arr::only($sms->toArray(), array_keys($attributes)));
    }
}
