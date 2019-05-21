<?php

namespace LBHurtado\Missive\Tests;

use LBHurtado\Missive\Classes\SMSAbstract;
use Mockery;
use Illuminate\Support\Arr;
use LBHurtado\Missive\Missive;
use LBHurtado\Missive\Models\SMS;
use LBHurtado\Missive\Facades\Missive as MissiveFacade;
use LBHurtado\Missive\Handlers\CreateSMSHandler;
use LBHurtado\Missive\Commands\CreateSMSCommand;

class MissiveTest extends TestCase
{
    /** @var LBHurtado\Missive\Missive */
    protected $missive;

    /** @var Mockery\Mock */
    protected $mockedMissive;

    /** @var LBHurtado\Missive\Handlers\CreateSMSHandler */
    protected $handler;

    /** @var LBHurtado\Missive\Commands\CreateSMSCommand */
    protected $command;

    public function setUp(): void
    {
        parent::setUp();

        $this->missive = app(Missive::class);
//        $this->mockedMissive = Mockery::mock(MissiveFacade::class);
//        $this->handler = new CreateSMSHandler($this->mockedMissive);
//        $this->handler = new CreateSMSHandler();
    }

    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    /** @test */
    public function missive_can_set_sms_and_read_sms_as_property()
    {
        /*** arrange ***/
        $from = '+639171234567'; $to = '+639187654321'; $message = 'Test Messages';
        $attributes = compact('from', 'to', 'message');

        /*** act ***/
        $this->missive->setSMS($sms = factory(SMS::class)->create($attributes));

        /*** assert ***/
        $this->assertSame($sms->id, SMS::where($attributes)->first()->id);
        $this->assertSame($sms->id, $this->missive->getSMS()->id);
    }

    //TODO: fix missive facade test
//    /** @test */
//    public function mock_missive_can_create_sms()
//    {
//        /*** arrange ***/
//        $from = '+639171234567'; $to = '+639187654321'; $message = 'Test Messages';
//        $attributes = compact('from', 'to', 'message');
//
//        $this->command = new CreateSMSCommand($attributes);
//
//        $this->mockedMissive
//            ->shouldReceive('createSMS')
//            ->once()
//            ->andReturn($sms = factory(SMS::class)->create($attributes));
//
//        /*** act ***/
//        $this->handler->handle($this->command);
//
//        /*** assert ***/
//        $this->assertEquals($attributes, Arr::only($sms->toArray(), array_keys($attributes)));
//    }
}
