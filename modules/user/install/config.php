<?php
return [
    'module' => [
        'class' => '\app\modules\user\Module',
    ],
    'components' => [

    /*    'authManager' => [
            // Будем использовать свой менеджер авторизации
            'class' => 'user\components\PhpAuthManager',
            // Роль по умолчанию. Все, кто не админы, модераторы и юзеры — гости и внутренние пользователи.
            'defaultRoles' => ['guest'],
        ],*

        'authorizationManager' => [
            'class' => 'user\components\AuthorizationManager',
            'pathAuth' => 'auth',
        ],
    */

        // компонент Yii::app()->user, подробнее http://www.yiiframework.ru/doc/guide/ru/topics.auth
        'user' => [
            'identityClass' => 'user\components\WebUser',
            'loginUrl' => ['/user/account/login'],
            'allowAutoLogin'=>true,
            'autoUpdateFlash'=>false,
            'identityCookie' => [
                'httpOnly' => true,
            ],
        ],
    /*
        'userManager' => [
            'class' => 'user\components\UserManager',
            'hasher' => [
                'class' => 'user\components\Hasher',
            ],
            'tokenStorage' => [
                'class' => 'user\components\TokenStorage',
            ],
        ],
        'authenticationManager' => [
            'class' => 'user\components\AuthenticationManager',
            'identity' => 'user\components\UserIdentity',
        ],
    */
    /*
        'eventManager' => [
            'class' => 'core\components\EventManager',
            'events' => [
                'user.success.registration' => [
                    ['\user\listeners\UserManagerListener', 'onUserRegistration'],
                ],
                'user.success.registration.need.activation' => [
                    ['\user\listeners\UserManagerListener', 'onUserRegistrationNeedActivation'],
                ],
                'user.success.password.recovery' => [
                    ['\user\listeners\UserManagerListener', 'onPasswordRecovery'],
                ],
                'user.success.activate.password' => [
                    ['\user\listeners\UserManagerListener', 'onSuccessActivatePassword'],
                ],
                'user.success.email.confirm' => [
                    ['\user\listeners\UserManagerListener', 'onSuccessEmailConfirm'],
                ],
                'user.success.email.change' => [
                    ['\user\listeners\UserManagerListener', 'onSuccessEmailChange'],
                ],
            ],
        ],
    */
    ],
    'rules' => [
        '/login' => 'user/account/login',
        '/logout' => 'user/account/logout',
        '/registration' => 'user/account/registration',
        '/recovery' => 'user/account/recovery',
    ],
];
