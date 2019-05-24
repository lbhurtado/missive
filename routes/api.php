<?php

use Illuminate\Support\Facades\Route;
use LBHurtado\Missive\Actions\{CreateSMSAction, VerifyContactAction};

Route::prefix('api')
    ->middleware('api')
    ->match(['get', 'post'], 'sms/relay', CreateSMSAction::class);

Route::prefix('api')
    ->middleware('api')
    ->match(['get', 'post'], 'sms/verify', VerifyContactAction::class);
