<?php
namespace app\modules\user;

use app\modules\core\auth\ModuleTask;
use app\modules\core\components\Module as ParentModule;
use app\modules\core\helpers\File;
use app\modules\user\components\Roles;
use Yii;

/**
 * Class Module
 * @package app\modules\user
 *
 * @property-read string $loginPage /user/account/login
 * @property-read string $registerPage /user/account/registration
 * @property-read string $profilePage user/profile/index
 * @property-read string $logoutPage /user/account/logout
 * @property-read string $recoveryPage /user/account/recovery
 */
class Module extends ParentModule
{
    public $defaultAvatar = 'default.png';

    public $emailAccountVerification = 1;

    public $avatarDirs = '@app/web/uploads/images/avatars/';

    public $registrationDisabled = 0;

    public $recoveryDisabled = 0;

    public $minPasswordLength = 6;

    public $generateUserName = 0;

    public $showCaptcha = 0;

    public $phonePattern = '/^((\+?7)(-?\d{3})-?)?(\d{3})(-?\d{4})$/';

    public $phoneMask = '+7-999-999-9999';

    /** @var bool Пользователь должен ли подтвердить свою учетную запись. */
    public $enableConfirmation = true;

    /** @var bool Разрешить ли вход в систему без подтверждения. */
    public $enableUnconfirmedLogin = false;

    public $autoRecoveryPassword = 0;

    public $sessionLifeTimeDate = 1;

    public $expireTokenActivationLifeHours = 6;

    public $expireTokenPasswordLifeHours = 10;



    /** @var int Cost параметр, используемый алгоритмом хеширования Blowfish. */
    public $cost = 10;


    public function init() {

        parent::init();

        $this->avatarDirs = Yii::getAlias($this->avatarDirs);
        File::checkPath($this->avatarDirs);

    }


    public static function Title() {

        return 'Модуль пользователей';
    }


    public function getMenuAdmin() {

        return [
            [
                'label'=>'<span class="hidden-xs">Пользователи</span>',
                'icon'=>'fa fa-fw fa-users',
                'items'=>[
                    [
                        'label' => 'Роли',
                        'visible'=>true //user()->checkAccess(RolesTask::TASK),
                    ],
                    [
                        'icon' => 'fa fa-fw fa-list-alt',
                        'label' => 'Список ролей',
                        'url'=>$this->getMenuUrl('roles/index'),
                        //'visible'=>user()->checkAccess(TaskRoles::OPERATION_READ)
                    ],
                    [
                        'icon' => 'fa fa-fw fa-user-plus',
                        'label' => 'Добавить роль',
                        'url'=>$this->getMenuUrl('roles/create'),
                        //'visible'=>user()->checkAccess(TaskRoles::OPERATION_EDIT)
                    ],
                    // ->
                    [
                        'label' => 'Пользователи',
                        'visible'=>true //user()->checkAccess(TaskManager::TASK)
                    ],
                    [
                        'icon' => 'fa fa-fw fa-list-alt',
                        'label' => 'Все пользователи',
                        'url'=>$this->getMenuUrl('manager/index'),
                    ],
                    [
                        'icon' => 'fa fa-fw fa-user-plus',
                        'label' => 'Добавить пользователя',
                        'url'=>$this->getMenuUrl('manager/create'),
                    ],
                    // ->
                    [
                        'label' => 'Токены',
                        'visible'=>true //user()->checkAccess(TokenTask::TASK),
                    ],
                    [
                        'icon' => 'fa fa-fw fa-list-alt',
                        'label' => 'Токены',
                        'url'=>$this->getMenuUrl('token/index'),
                    ],
                    // -----
                    [
                        'label' => '',
                        'options' => [
                            'role' => 'separator',
                            'class' => 'divider',
                        ],
                        'visible'=>user()->can(ModuleTask::OPERATION_SETTINGS),
                    ],
                    [
                        'label' => 'Настройки',
                        'icon' => 'fa fa-fw fa-cog',
                        'url' => ['/core/module/settings', 'module'=>'user'],
                        'visible'=>user()->can(ModuleTask::OPERATION_SETTINGS)
                    ],
                ],
                'visible'=>user()->can(Roles::ADMIN),
            ],
        ];
    }



    public function getLoginPage() {

        return  '/user/account/login';
    }


    public function getRegisterPage() {

        return  '/user/account/registration';
    }


    public function getProfilePage() {

        return  'user/profile/index';
    }


    public function getLogoutPage() {

        return  '/user/account/logout';
    }


    public function getRecoveryPage() {

        return  '/user/account/recovery';
    }
}
