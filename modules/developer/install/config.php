<?php

$config = [
    'module' => [
        'class' => '\app\modules\developer\Module',
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        //'allowedIPs' => ['127.0.0.1', '::1'],
        // uncomment the following to add your IP if you are not connecting from localhost.
        'generators' => [
            'module' => [
                'class' => 'app\modules\developer\generators\module\Generator',
                'templates' => [
                    'default' => '@app/modules/developer/generators/module/default',
                ]
            ],
            'controller'=>[
                'class' => 'app\modules\developer\generators\controller\Generator',
                'templates' => [
                    'default' => '@app/modules/developer/generators/controller/default',
                ]
            ],
            'console'=>[
                'class' => 'app\modules\developer\generators\console\Generator',
                'templates' => [
                    'default' => '@app/modules/developer/generators/console/default',
                ]
            ],
            'crud'=>[
                'class' => 'app\modules\developer\generators\crud\Generator',
                'templates' => [
                    'default' => '@app/modules/developer/generators/crud/default',
                ]
            ],
        ],
    ];


    $config['components']['urlManager']['rules'] =
        [
            'gii' => 'gii',
            'gii/<controller:\w+>' => 'gii/<controller>',
            'gii/<controller:\w+>/<action:\w+>' => 'gii/<controller>/<action>',
        ];
}

return $config;