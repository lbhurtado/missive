<?php

namespace LBHurtado\Missive\Http\Controllers;

use Illuminate\Http\Request;

class SMSController
{
    //TODO: deprecate
	public function __invoke(Request $request)
	{
		return 'LBHurtado\Missive\Http\Controllers\SMSController';
	}
}
