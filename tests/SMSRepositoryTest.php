<?php

namespace LBHurtado\Missive\Tests;

use Illuminate\Support\Arr;
use LBHurtado\Missive\Models\SMS;
use LBHurtado\Missive\Repositories\{SMSRepository, SMSRepositoryEloquent};

class SMSRepositoryTest extends TestCase
{
    /** @test */
    public function sms_repository_binds_to_sms_repository_eloquent()
    {
        /*** arrange ***/
        /*** act ***/
        $smss = app(SMSRepository::class);

        /*** assert ***/
        $this->assertInstanceOf(SMSRepositoryEloquent::class, $smss);
    }

    /** @test */
    public function sms_repository_can_create_sms()
    {
        /*** arrange ***/
        $from = '+639171234567'; $to = '+639187654321'; $message = 'Test Messages';

        /*** act ***/
        app(SMSRepository::class)->create($attributes = compact('from', 'to', 'message'));

        /*** assert ***/
        $this->assertDatabaseHas(config('missive.table_names.smss'), compact($attributes));
    }
}
