<?php

namespace LBHurtado\Missive\Tests;

use LBHurtado\Missive\Commands\CreateSMSCommand;
use LBHurtado\Missive\Transforms\CreateSMSTransform;

class CreateSMSTransformTest extends TestCase
{
	protected $transfom;

    public function setUp(): void
    {
        parent::setUp();

        $this->transfom = new CreateSMSTransform;
    }

    /** @test */
    public function transform_can_do_local()
    {
        /*** arrange ***/
        $from = '09171234567'; $to = '09187654321'; $message = ' Test Messages ';
        $attributes = compact('from', 'to', 'message');

        /*** act */
        $command = new CreateSMSCommand($attributes);

        /*** assert ***/
		$this->transfom->execute($command, function (CreateSMSCommand $cmd) use ($attributes) {
            $this->assertEquals([
            	'from' => '+639171234567',
            	'to' => '+639187654321',
            	'message' => 'Test Messages'
            ], 
            $cmd->getProperties());
        });
    }

    /** @test */
    public function transform_can_do_telerivet()
    {
        /*** arrange ***/
        $this->app['config']->set('missive.relay.default', 'telerivet');
        $from_number = '09171234567'; $to_number = '09187654321'; $content = ' Test Messages ';
        $attributes = compact('from_number', 'to_number', 'content');

        /*** act */
        $command = new CreateSMSCommand($attributes);

        /*** assert ***/
		$this->transfom->execute($command, function (CreateSMSCommand $cmd) use ($attributes) {
            $this->assertEquals([
            	'from_number' => '+639171234567',
            	'to_number' => '+639187654321',
            	'content' => 'Test Messages'
            ], 
            $cmd->getProperties());
        });
    }
}