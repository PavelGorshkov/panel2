<?php
return [
    'module' => [
        'class' => 'app\modules\finance\Module',
    ],
    'components'=> [
        'dbUis'=>[
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=prod_uis',
            'username' => 'prod',
            'password' => 'GhjlernjdYtn!',
            'charset' => 'utf8',
            'tablePrefix'=>''
        ],
    ]
];
