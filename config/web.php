<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$webservice = require __DIR__.'/webservice.php';

$id = 'panel2';

$config = [
    'id' => $id,
    'name'=>'Панель',
    'language'=>'ru',
    'basePath' => dirname(__DIR__),

    'bootstrap' => ['log'],

    'layout' => '@app/modules/core/views/layouts/admin.php',

    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@kvgrid'   => '@vendor/kartik-v/yii2-grid',
    ],

    'modules'=>[

        'core'=> [
            'class' => 'app\modules\core\Module',
        ],
        'user' => [
            'class' => 'app\modules\user\Module',
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
            'i18n' => [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@kvgrid/messages',
                'forceTranslation' => true
            ],
        ],
    ],

    'components' => [

        'authManager' => [
            'class' => 'app\modules\user\components\PhpManager',
            'defaultRoles' => ['guest'],
        ],

        'buildAuthManager' => [
            'class' => '\app\modules\user\components\BuildAuthManager',
        ],

        'cache' => [
            'class' => '\yii\caching\FileCache',
            //'class' => 'yii\caching\MemCache',
        ],

        'ws'=>$webservice,

        'db' => $db,

        'errorHandler' => [
            'errorAction' => 'site/error',
            'errorView'=>'@app/modules/core/views/errorHandler/error.php'
        ],

        'formatter'=>[
            'currencyCode'=>'RUB',
        ],

        'i18n'=>[
            'translations' => [
                'kvgrid' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@kvgrid/messages',
                    //'sourceLanguage' => 'en-US',
                    /*'fileMap' => [
                        'app'       => 'app.php',
                        'app/error' => 'error.php',
                    ],*/
                ],
            ],
        ],

        'ldap' => [
            'class' => '\app\modules\core\components\AdLdapComponent',
            'options' => [
                'user_ldap'=>[
                    'domain_controllers' => ['ad.marsu.ru'],
                    'base_dn' => 'DC=ad,DC=marsu,DC=ru',
                    'admin_username' => 'yii',
                    'admin_password' => 'v7vkk$3p',

                    //  'account_prefix'        => 'ACME-',
                    'account_suffix' => '@ad.marsu.ru',
                    //  'admin_account_prefix'  => 'ACME-ADMIN-',
                    //  'admin_account_suffix'  => '@acme.org',
                    //'port' => 3268,
                    'port' => 389,
                    'follow_referrals' => false,
                    'use_ssl' => false,
                    'use_tls' => false,
                    'timeout' => 5,
                ],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3: 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class'=> '\app\modules\core\components\BitrixTarget',
                    'url'=>'https://corp.marsu.ru/marsu/send_message2support.php',
                    'debug'=>true,
                ]
            ],
        ],

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],

        'menuManager'=>[
            'class'=>'\app\modules\core\components\MenuManager',
        ],

        'migrator'=>[
            'class'=>'\app\modules\core\components\Migrator',
        ],

        'moduleManager'=>[
            'class'=>'\app\modules\core\components\ModuleManager',
        ],

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'qjnYa_W_yuARSOqWA2_Kx1uDVySXWoAp',
        ],

        'session'=>[
            'name'=>'_'.$id,
        ],

        'thumbNailer'=> [
            'class'=>'\app\modules\core\components\Thumbnailer',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '/login' => 'user/account/login',
                '/logout' => 'user/account/logout',
                '/registration' => 'user/account/registration',
                '/activation' => 'user/account/activation',
                '/recovery-password' => 'user/account/recovery-password',
                '/recovery' => 'user/account/recovery',
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],

        'user' => [
            'class'=>'\app\modules\user\components\WebUser',
            'identityClass' => '\app\modules\user\models\IdentityUser',
            'enableAutoLogin' => true,
            'loginUrl' => ['/user/account/login'],
            'identityCookie' => [
                'name' => '_identity_'.$id,
                'httpOnly' => true,
            ],
        ],

        'userManager'=> [
            'class' => 'app\modules\user\components\UserManager',
        ],

        'view'=>[
            'class' => 'app\modules\core\components\View',
        ],
    ],

    'params' => $params,
];

if (YII_ENV_DEV) {

    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;