<?php
namespace app\modules\user;

use app\modules\core\components\Module as ParentModule;

/**
 * core module definition class
 */
class Module extends ParentModule
{
    public $accountActivationSuccess = '/user/account/login';

    public $accountActivationFailure = '/user/account/registration';

    public $loginPage = '/user/account/login';

    public $loginSuccess = 'user/profile/index';

    public $logoutPage = '/user/account/logout';

    public $registrationSuccess = '/user/account/login';

    /** @var int Cost параметр, используемый алгоритмом хеширования Blowfish. */
    public $cost = 10;


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
                        'visible'=>user()->checkAccess(TaskRoles::TASK),
                    ],
                    [
                        'icon' => 'fa fa-fw fa-list-alt',
                        'label' => 'Список ролей',
                        'url'=>$this->getMenuUrl('roles/index'),
                        'visible'=>user()->checkAccess(TaskRoles::OPERATION_READ)
                    ],
                    [
                        'icon' => 'fa fa-fw fa-user-plus',
                        'label' => 'Добавить роль',
                        'url'=>$this->getMenuUrl('roles/create'),
                        'visible'=>user()->checkAccess(TaskRoles::OPERATION_EDIT)
                    ],
                    // ->
                    [
                        'label' => 'Пользователи',
                        'visible'=>user()->checkAccess(TaskManager::TASK)
                    ],
                    [
                        'icon' => 'fa fa-fw fa-list-alt',
                        'label' => 'Все пользователи',
                        'url'=>$this->getMenuUrl('manager/index'),
                        'visible'=>user()->checkAccess(TaskManager::OPERATION_READ)
                    ],
                    [
                        'icon' => 'fa fa-fw fa-user-plus',
                        'label' => 'Добавить пользователя',
                        'url'=>$this->getMenuUrl('manager/create'),
                        'visible'=>user()->checkAccess(TaskManager::OPERATION_EDIT)
                    ],
                    // ->
                    [
                        'label' => 'Токены',
                        'visible'=>user()->checkAccess(TaskToken::TASK),
                    ],
                    [
                        'icon' => 'fa fa-fw fa-list-alt',
                        'label' => 'Токены',
                        'url'=>$this->getMenuUrl('token/index'),
                        'visible'=>user()->checkAccess(TaskToken::OPERATION_READ)
                    ],
                    // -----
                    [
                        'label' => '',
                        'itemOptions' => [
                            'role' => 'separator',
                            'class' => 'divider',
                            'visible'=>app()->moduleManager->visibleItemMenu('core', TaskModule::OPERATION_SETTINGS),
                        ],
                    ],
                    [
                        'label' => 'Настройки',
                        'icon' => 'fa fa-fw fa-cog',
                        'url' => ['/core/module/settings', 'module'=>'user'],
                        'visible'=>app()->moduleManager->visibleItemMenu('core', TaskModule::OPERATION_SETTINGS)
                    ],
                ],
                'visible'=>user()->checkAccess(Roles::ADMIN),
            ],
        ];
    }

}
