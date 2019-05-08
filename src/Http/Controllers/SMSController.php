<?php

namespace LBHurtado\Missive\Http\Controllers;

use Illuminate\Http\Request;
use LBHurtado\Missive\Models\{SMS, Contact, Relay};
use LBHurtado\Missive\Jobs\{CreateSMS, CreateContact, CreateRelay};

class SMSController
{
	public function __invoke(Request $request)
	{
//        CreateSMS::dispatchNow($request->all());
//
//        CreateContact::dispatchNow($request->from);
//        CreateRelay::dispatchNow($request->to);

        CreateSMS::dispatch($request->all())->chain([
            new CreateContact('1234567'),
            new CreateRelay('2345678'),
        ]);

		return $request;
	}
}
