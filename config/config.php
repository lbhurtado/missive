<?php

return [
	'table_names' => [
        'airtime_contact' => 'airtime_contact',
        'airtimes' => 'airtimes',
		'contacts' => 'contacts',
        'smss'     => 's_m_s_s',
		'relays'   => 'relays'
	],
    'classes' => [
        'models' => [
            'airtime' => \LBHurtado\Missive\Models\Airtime::class,
            'contact' => \LBHurtado\Missive\Models\Contact::class,
            'relay' => \LBHurtado\Missive\Models\Relay::class,
            'sms' => \LBHurtado\Missive\Models\SMS::class
        ],
        'pivots' => [
            'airtime_contact' => \LBHurtado\Missive\Pivots\AirtimeContact::class
        ],
        'commands' => [
            'sms' => [
                'create' => \LBHurtado\Missive\Commands\CreateSMSCommand::class
            ]
        ],
        'handlers' => [
            'sms' => [
                'create' => \LBHurtado\Missive\Handlers\CreateSMSHandler::class
            ]
        ],
        'middlewares' => [
            'sms' => [
                \LBHurtado\Missive\Validators\CreateSMSValidator::class,
                \LBHurtado\Missive\Responders\CreateSMSResponder::class,
//                \LBHurtado\Missive\Actions\Middleware\ChargeSMSMiddleware::class,
            ]
        ]
    ],
];
