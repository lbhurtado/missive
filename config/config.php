<?php

return [
	'table_names' => [
		'smss'     => 's_m_s_s',
		'contacts' => 'contacts',
		'relays'   => 'relays'
	],
    'classes' => [
        'commands' => [
            'sms.create' => \LBHurtado\Missive\Commands\CreateSMSCommand::class
        ],
    ]
];
