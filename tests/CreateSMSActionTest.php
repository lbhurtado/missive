<?php

namespace LBHurtado\Missive\Tests;

use Opis\Events\EventDispatcher;
use Illuminate\Support\Facades\Request;
use LBHurtado\Missive\Actions\CreateSMSAction;
use LBHurtado\Missive\Models\{Airtime, Contact};
use Joselfonseca\LaravelTactician\CommandBusInterface;
use LBHurtado\Missive\Actions\Middleware\ChargeSMSMiddleware;

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
}
