<?php

namespace LBHurtado\Missive\Tests;

use Mockery;
use Illuminate\Support\Arr;
use LBHurtado\Missive\Missive;
use LBHurtado\Missive\Models\SMS;
use LBHurtado\Missive\Routing\Router;

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

    /** @var \LBHurtado\Missive\Routing\Router */
    protected $router;

    public function setUp(): void
    {
        parent::setUp();

        $this->missive = app(Missive::class);
        $this->mockedMissive = Mockery::mock(Missive::class);
        $this->router = new Router($this->mockedMissive);
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

    /** @test */
    public function mock_missive_should_set_and_get_sms_when_router_is_processing()
    {
        /*** arrange ***/
        $from = '+639171234567'; $to = '+639187654321'; $message = 'Test Messages';
        $attributes = compact('from', 'to', 'message');

        $this->mockedMissive->shouldReceive('setSMS')->once();

        $this->mockedMissive
            ->shouldReceive('getSMS')
            ->once()
            ->andReturn($sms = factory(SMS::class)->create($attributes))
        ;

        /*** act ***/
        $this->router->process($sms);

        /*** assert ***/
        $this->assertEquals($attributes, Arr::only($sms->toArray(), array_keys($attributes)));
    }
}
