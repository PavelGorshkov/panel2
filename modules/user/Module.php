<?php

namespace app\modules\user;

use app\modules\core\auth\ModuleTask;
use app\modules\core\components\Module as ParentModule;
use app\modules\core\helpers\File;
use app\modules\user\components\Roles;
use app\modules\user\helpers\UserAccessLevelHelper;
use app\modules\user\helpers\UserSettings;
use yii\console\Application;
use yii\web\Request;

/**
 * Class Module
 * @package app\modules\user
 *
 * @property-read array $loginPage /user/account/login
 * @property-read array $registerPage /user/account/registration
 * @property-read array $logoutPage /user/account/logout
 * @property-read array $recoveryPage /user/account/recovery
 * @property-read string $startPage
 */
class Module extends ParentModule
{
    /**
     * @var int Автовостановление пароля
     */
    public $autoRecoveryPassword = 0;

    public $avatarDirs = '@app/web/uploads/images/avatars/';

    public $avatarExtensions = 'jpg, png, gif, jpeg';

    public $avatarMaxSize = 5242880;

    /** @var int Cost параметр, используемый алгоритмом хеширования Blowfish. */
    public $cost = 10;

    public $defaultAvatar = 'default.png';

    /** @var int Подтверждение аккаунта по email */
    public $emailAccountVerification = 0;

    /** @var int Пользователь должен ли подтвердить свою учетную запись. */
    public $enableConfirmation = 0;

    /** @var int Разрешить ли вход в систему без подтверждения. */
    public $enableUnconfirmedLogin = 0;

    /** @var int Срок действия токена активации (в часах) */
    public $expireTokenActivationLifeHours = 6;

    /** @var int Срок действия токена восстановления пароля (в часах) */
    public $expireTokenPasswordLifeHours = 10;

    /** @var int Метод аутентификации */
    public $fromAuthorization = 1;

    /** @var int Автогенерация логина */
    public $generateUserName = 0;

    public $startWebPage = 'user/profile/index';

    private $_startPage;

    /**
     * @var int
     */
    public $ldapRole = UserAccessLevelHelper::LEVEL_USER;

    /** @var int Минимальная длина пароля */
    public $minPasswordLength = 6;

    public $phoneMask = '+7-999-999-9999';

    public $phonePattern = '/^((\+?7)(-?\d{3})-?)?(\d{3})(-?\d{4})$/';

    /** @var int Отключение восстановления */
    public $recoveryDisabled = 0;

    /** @var int */
    public $registrationDisabled = 1;

    /** @var int Срок хранения сессии (в днях) */
    public $sessionLifeTimeDate = 1;

    /** @var int Отображать капчу */
    public $showCaptcha = 0;



    /**
     * @return array
     */
    public function getParamLabels()
    {
        return [
            'autoRecoveryPassword' => 'Автовосстановление пароля',
            'emailAccountVerification' => 'Подтверждение аккаунта по email',//
            'enableConfirmation' => 'Пользователь должен ли подтвердить свою учетную запись',
            'enableUnconfirmedLogin' => 'Разрешить ли вход в систему без подтверждения',
            'expireTokenActivationLifeHours' => 'Срок действия токенов (в часах)',//
            'expireTokenPasswordLifeHours' => 'Срок действия токена восстановления пароля (в часах)',//
            'fromAuthorization' => 'Метод авторизации',//
            'generateUserName' => 'Автогенерация логина',//
            'ldapRole' => 'Роль при LDAP авторизации',//
            'minPasswordLength' => 'Минимальная длина пароля',//
            'phoneMask' => 'Маска телефона',//
            'phonePattern' => 'Регулярное выражение телефона',//
            'recoveryDisabled' => 'Отключение восстановления',
            'registrationDisabled' => 'Отключение регистрации',
            'sessionLifeTimeDate' => 'Срок хранения сессии (в днях)',
            'showCaptcha' => 'Отображать капчу',//
            'startWebPage' => 'Стартовая страница',//
        ];
    }


    /**
     * @return array
     */
    public function getParamsDropdown()
    {
        return [
            'autoRecoveryPassword' => $this->choiseList(),
            'emailAccountVerification' => $this->choiseList(),
            'enableConfirmation' => $this->choiseList(),
            'enableUnconfirmedLogin' => $this->choiseList(),
            'fromAuthorization' => $this->fromAuthorizationList(),
            'generateUserName' => $this->choiseList(),
            'ldapRole' => UserAccessLevelHelper::getListUFRole(),
            'recoveryDisabled' => $this->choiseList(),
            'registrationDisabled' => $this->choiseList(),
            'showCaptcha' => $this->choiseList(),
        ];
    }


    /**
     * @return array
     */
    public function getParamGroups()
    {
        return [
            [
                'title' => 'Основные данные',
                'items' => [
                    'startWebPage',
                ]
            ],
            [
                'title' => 'Регистрация',
                'items' => [
                    'emailAccountVerification',
                    'fromAuthorization',
                    'generateUserName',
                    'minPasswordLength',
                    'phoneMask',
                    'phonePattern',
                    'showCaptcha',
                    'ldapRole',
                ]
            ],
            [
                'title' => 'Сроки хранения состояний',
                'items' => [
                    'expireTokenActivationLifeHours',
                    'expireTokenPasswordLifeHours',
                    'sessionLifeTimeDate',
                ],

            ]
        ];
    }


    /**
     * @return array
     */
    public function choiseList()
    {
        return [
            0 => 'Нет',
            1 => 'Да',
        ];
    }


    /**
     * @return array
     */
    public function fromAuthorizationList()
    {
        return [
            '0' => 'Форма регистрации',
            '1' => 'LDAP регистрация',
        ];
    }


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->avatarDirs = \Yii::getAlias($this->avatarDirs);
        File::checkPath($this->avatarDirs);

        $this->setVersion('1.0.0');

        if (!(app() instanceof Application)) {

            $this->_startPage = $this->startWebPage;
            $startPage = UserSettings::model()->startPage;

        } else {

            $startPage = null;
        }

        if (!empty($startPage)) {

            $this->_startPage = $startPage;
        }
    }


    /**
     * @return string
     */
    public static function Title()
    {
        return 'Модуль пользователей';
    }


    /**
     * @return bool
     */
    public function isFromLDAP()
    {
        return $this->fromAuthorization === 1;
    }


    /**
     * @return array
     */
    public function getMenuAdmin()
    {
        return [
            [
                'label' => '<span class="hidden-xs">Пользователи</span>',
                'icon' => 'fa fa-fw fa-users',
                'items' => [
                    [
                        'label' => 'Группы',
                        'visible' => true //user()->checkAccess(RolesTask::TASK),
                    ],
                    [
                        'icon' => 'fa fa-fw fa-list-alt',
                        'label' => 'Список групп',
                        'url' => $this->getMenuUrl('roles/index'),
                        //'visible'=>user()->checkAccess(TaskRoles::OPERATION_READ)
                    ],
                    [
                        'icon' => 'fa fa-fw fa-user-plus',
                        'label' => 'Добавить группу',
                        'url' => $this->getMenuUrl('roles/create'),
                        //'visible'=>user()->checkAccess(TaskRoles::OPERATION_EDIT)
                    ],
                    // ->
                    [
                        'label' => 'Пользователи',
                        'visible' => true //user()->checkAccess(TaskManager::TASK)
                    ],
                    [
                        'icon' => 'fa fa-fw fa-list-alt',
                        'label' => 'Все пользователи',
                        'url' => $this->getMenuUrl('manager/index'),
                    ],
                    [
                        'icon' => 'fa fa-fw fa-user-plus',
                        'label' => 'Добавить пользователя',
                        'url' => $this->getMenuUrl('manager/create'),
                    ],
                    // ->
                    [
                        'label' => 'Токены',
                        'visible' => true //user()->checkAccess(TokenTask::TASK),
                    ],
                    [
                        'icon' => 'fa fa-fw fa-list-alt',
                        'label' => 'Токены',
                        'url' => $this->getMenuUrl('token/index'),
                    ],
                    // -----
                    [
                        'label' => '',
                        'options' => [
                            'role' => 'separator',
                            'class' => 'divider',
                        ],
                        'visible' => user()->can(ModuleTask::OPERATION_SETTINGS),
                    ],
                    [
                        'label' => 'Настройки',
                        'icon' => 'fa fa-fw fa-cog',
                        'url' => ['/core/module/settings', 'module' => 'user'],
                        'visible' => user()->can(ModuleTask::OPERATION_SETTINGS)
                    ],
                ],
                'visible' => user()->can(Roles::ADMIN),
            ],
        ];
    }


    /**
     * @return array
     */
    public function getLoginPage()
    {
        return ['/user/account/login'];
    }


    /**
     * @return array
     */
    public function getRegisterPage()
    {
        return ['/user/account/registration'];
    }


    /**
     * @return string
     */
    public function getStartPage()
    {
        if (!is_array($this->_startPage)) {

            if (!empty(app()->request->baseUrl)) {

                $baseUrl = app()->request->baseUrl;

                $this->_startPage = $baseUrl . '/'. ltrim($this->_startPage, $baseUrl);
            }

            $request = new Request();
            $request->setUrl($this->_startPage);

            $startPage = app()->urlManager->parseRequest($request);

        } else {

            $startPage = $this->_startPage;
        }

        return $startPage;
    }


    /**
     * @return array
     */
    public function getLogoutPage()
    {
        return ['/user/account/logout'];
    }


    /**
     * @return array
     */
    public function getRecoveryPage()
    {
        return ['/user/account/recovery'];
    }
}
