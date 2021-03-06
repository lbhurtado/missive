<?php

namespace LBHurtado\Missive\Tests;

use Illuminate\Support\Str;
use LBHurtado\Missive\Commands\CreateSMSCommand;
use LBHurtado\Missive\Validators\CreateSMSValidator;
use LBHurtado\Missive\Exceptions\CreateSMSValidationException;

class CreateSMSValidatorTest extends TestCase
{
    /** @var \LBHurtado\Missive\Validators\CreateSMSValidator */
    protected $validator;

    public function setUp(): void
    {
        parent::setUp();

        $this->validator = new CreateSMSValidator;
    }

    /** @test */
    public function command_from_field_cannot_be_null()
    {
        /*** arrange ***/
        $from = null; $to = '+639187654321'; $message = 'Test Messages';
        $attributes = compact('from', 'to', 'message');

        /*** act */
        $command = new CreateSMSCommand($attributes);

        $this->expectException(CreateSMSValidationException::class);
        $this->validator->execute($command, function (CreateSMSCommand $cmd) {
            $this->assertTrue(false);
        });
    }

    /** @test */
    public function command_from_field_cannot_be_empty()
    {
        /*** arrange ***/
        $from = ''; $to = '+639187654321'; $message = 'Test Messages';
        $attributes = compact('from', 'to', 'message');

        /*** act */
        $command = new CreateSMSCommand($attributes);

        $this->expectException(CreateSMSValidationException::class);
        $this->validator->execute($command, function (CreateSMSCommand $cmd) {
            $this->assertTrue(false);
        });
    }

    /** @test */
    public function command_to_field_cannot_be_null()
    {
        /*** arrange ***/
        $from = '+639171234567'; $to = null; $message = 'Test Messages';
        $attributes = compact('from', 'to', 'message');

        /*** act */
        $command = new CreateSMSCommand($attributes);

        $this->expectException(CreateSMSValidationException::class);
        $this->validator->execute($command, function (CreateSMSCommand $cmd) {
            $this->assertTrue(false);
        });
    }

    /** @test */
    public function command_to_field_cannot_be_empty()
    {
        /*** arrange ***/
        $from = '+639171234567'; $to = null; $message = 'Test Messages';
        $attributes = compact('from', 'to', 'message');

        /*** act */
        $command = new CreateSMSCommand($attributes);

        $this->expectException(CreateSMSValidationException::class);
        $this->validator->execute($command, function (CreateSMSCommand $cmd) {
            $this->assertTrue(false);
        });
    }

    /** @test */
    public function command_message_field_cannot_be_null()
    {
        /*** arrange ***/
        $from = '+639171234567'; $to = '+639187654321'; $message = null;
        $attributes = compact('from', 'to', 'message');

        /*** act */
        $command = new CreateSMSCommand($attributes);

        $this->expectException(CreateSMSValidationException::class);
        $this->validator->execute($command, function (CreateSMSCommand $cmd) {
            $this->assertTrue(false);
        });
    }

    /** @test */
    public function command_message_field_can_be_empty()
    {
        /*** arrange ***/
        $from = '+639171234567'; $to = '+639187654321'; $message = '';
        $attributes = compact('from', 'to', 'message');

        /*** act */
        $command = new CreateSMSCommand($attributes);

        $this->validator->execute($command, function (CreateSMSCommand $cmd) {
            $this->assertTrue(true);
        });
    }

    /** @test */
    public function command_message_field_can_have_800_chars()
    {
        /*** arrange ***/
        $from = '+639171234567'; $to = '+639187654321'; $message = Str::random(800);
        $attributes = compact('from', 'to', 'message');

        /*** act */
        $command = new CreateSMSCommand($attributes);

        $this->validator->execute($command, function (CreateSMSCommand $cmd) {
            $this->assertTrue(true);
        });
    }

    /** @test */
    public function command_message_field_cannot_have_more_than_800_chars()
    {
        /*** arrange ***/
        $from = '+639171234567'; $to = '+639187654321'; $message = Str::random(801);
        $attributes = compact('from', 'to', 'message');

        /*** act */
        $command = new CreateSMSCommand($attributes);

        $this->expectException(CreateSMSValidationException::class);
        $this->validator->execute($command, function (CreateSMSCommand $cmd) {
            $this->assertTrue(false);
        });
    }
}
