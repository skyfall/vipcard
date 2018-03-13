<?php
return [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    	'db' => [
    		'class' => 'yii\db\Connection',
    		'dsn' => 'mysql:host=127.0.0.1;dbname=card',
    		'username' => 'root',
    		'password' => '',
    		'charset' => 'utf8',
    	],
    ],
];
