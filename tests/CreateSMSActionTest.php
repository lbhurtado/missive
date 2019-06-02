<?php

namespace LBHurtado\Missive\Tests;

use Opis\Events\EventDispatcher;
use Illuminate\Support\Facades\Request;
use LBHurtado\Missive\Actions\CreateSMSAction;
use LBHurtado\Missive\Validators\CreateSMSValidator;
use LBHurtado\Missive\Models\{Airtime, Contact, SMS};
use Joselfonseca\LaravelTactician\CommandBusInterface;
use LBHurtado\Missive\Actions\Middleware\ChargeSMSMiddleware;
use LBHurtado\Missive\Exceptions\CreateSMSValidationException;

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
        $from = $this->newFakeMobile(); $to = $this->newFakeMobile(); $message = $this->faker->sentence;
        $request = Request::create('/api/sms/relay', 'POST', $attributes = compact('from', 'to', 'message'));

        /*** act */
        $action = new CreateSMSAction($this->bus, $this->dispatcher, $request);
        $response = $action->__invoke();

        /*** assert ***/
        $this->assertDatabaseHas('s_m_s_s', $attributes);

        //TODO: test $response

        if (in_array(ChargeSMSMiddleware::class, $action->getMiddlewares())) {
            $this->assertDatabaseHas('airtime_contact', [
                'contact_id' => Contact::where(['mobile' => $from])->first()->id,
                'airtime_id' => Airtime::where(['key' => 'incoming-sms'])->first()->id,
                'qty' => 1
            ]);
        }
    }

    /** @test */
    public function action_validates_from_input()
    {
        /*** arrange ***/
        $from = '1234'; $to = $this->newFakeMobile(); $message = $this->faker->sentence;
        $request = Request::create('/api/sms/relay', 'POST', $attributes = compact('from', 'to', 'message'));

        /*** assert ***/
        $this->expectException(CreateSMSValidationException::class);

        /*** act */
        (new CreateSMSAction($this->bus, $this->dispatcher, $request))->__invoke();
    }

    /** @test */
    public function action_validates_to_input()
    {
        /*** arrange ***/
        $from = $this->newFakeMobile(); $to = null; $message = $this->faker->sentence;
        $request = Request::create('/api/sms/relay', 'POST', $attributes = compact('from', 'to', 'message'));

        /*** assert ***/
        $this->expectException(CreateSMSValidationException::class);

        /*** act */
        (new CreateSMSAction($this->bus, $this->dispatcher, $request))->__invoke();
    }

    /** @test */
    public function action_validates_message_input_cannot_be_null()
    {
        /*** arrange ***/
        $from = $this->newFakeMobile(); $to = $this->newFakeMobile(); $message = null;
        $request = Request::create('/api/sms/relay', 'POST', $attributes = compact('from', 'to', 'message'));

        /*** assert ***/
        $this->expectException(CreateSMSValidationException::class);

        /*** act */
        (new CreateSMSAction($this->bus, $this->dispatcher, $request))->__invoke();
    }

    /** @test */
    public function action_validates_message_input_can_be_empty()
    {
        /*** arrange ***/
        $from = $this->newFakeMobile(); $to = $this->newFakeMobile(); $message = '';
        $request = Request::create('/api/sms/relay', 'POST', $attributes = compact('from', 'to', 'message'));

        /*** act */
        (new CreateSMSAction($this->bus, $this->dispatcher, $request))->__invoke();

        /*** assert ***/
        $this->assertDatabaseHas('s_m_s_s', $attributes);
    }
}
