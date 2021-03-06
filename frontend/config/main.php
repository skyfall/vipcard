<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
	'defaultRoute' => 'card',
	'layout' => 'card', 
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'frontend\models\dataModels\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        	'loginUrl'=>['card/login']
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                		'class' => 'yii\log\FileTarget',
                		'levels' => ['error'],
                		'logVars' => ['*'],
                		//'categories' => ['application'],
                		'logFile' => '@runtime/logs/'.date('Ymd').'error.log',
                ],
            	[
            			'class' => 'yii\log\FileTarget',
            			'levels' => ['warning'],
            			'logVars' => ['*'],
            			//'categories' => ['application'],
            			'logFile' => '@runtime/logs/'.date('Ymd').'warning.log',
           		],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];
