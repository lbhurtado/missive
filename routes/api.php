<?php

use Illuminate\Support\Facades\Route;
use LBHurtado\Missive\Actions\CreateSMSAction;

Route::prefix('api')
    ->middleware('api')
    ->match(['get', 'post'], 'sms/relay', CreateSMSAction::class);
