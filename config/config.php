<?php

return [
	'table_names' => [
		'smss'     => 's_m_s_s',
		'contacts' => 'contacts',
		'relays'   => 'relays'
	],
    'classes' => [
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
                \LBHurtado\Missive\Responders\CreateSMSResponder::class
            ]
        ]
    ]
];
