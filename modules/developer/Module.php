<?php

namespace app\modules\developer;

use app\modules\core\auth\ModuleTask;
use app\modules\core\components\Module as ParentModule;
use app\modules\developer\auth\CreateAuthTask;
use app\modules\developer\auth\MigrationTask;
use app\modules\user\components\Roles;

/**
 * Class Module
 * @package app\modules\developer
 */
class Module extends ParentModule
{
    public static function Title() {

        return 'Разработка';
    }


    public function getMenuAdmin() {

        return [
            [
                'label' => '<span class="hidden-xs">Разработка</span>',
                'icon' => 'fa fa-fw fa-wrench',
                'items' => [
                    [
                        'label' => 'Миграции',
                        'visible' => user()->can(MigrationTask::TASK),
                    ],
                    [
                        'icon' => 'fa fa-fw fa-list-alt',
                        'label' => 'Список миграций',
                        'url' => $this->getMenuUrl('migration/index'),
                        'visible' => user()->can(MigrationTask::OPERATION_READ)
                    ],
                    [
                        'icon' => 'fa fa-fw fa-plus',
                        'label' => 'Добавить миграцию',
                        'url' => $this->getMenuUrl('migration/create'),
                        'visible' => user()->can(MigrationTask::OPERATION_EDIT)
                    ],
                    [
                        'label' => '',
                        'itemOptions' => [
                            'role' => 'separator',
                            'class' => 'divider',
                            'visible' => user()->can(MigrationTask::TASK),
                        ],
                    ],
                    /* ----- */
                    [
                        'label' => 'RBAC',
                        'visible' => user()->can(CreateAuthTask::TASK),
                    ],
                    [
                        'icon' => 'fa fa-fw fa-list-alt',
                        'label' => 'Список задач',
                        'url' => $this->getMenuUrl('auth/index'),
                        'visible' => user()->can(CreateAuthTask::OPERATION_READ)
                    ],
                    [
                        'icon' => 'fa fa-fw fa-plus',
                        'label' => 'Добавить задачу',
                        'url' => $this->getMenuUrl('auth/create'),
                        'visible' => user()->checkAccess(CreateAuthTask::OPERATION_CREATE)
                    ],
                    [
                        'label' => '',
                        'itemOptions' => [
                            'role' => 'separator',
                            'class' => 'divider',
                            'visible' => user()->checkAccess(CreateAuthTask::TASK),
                        ],
                    ],
                    /* ----- */
                    [
                        'label' => 'Генераторы',
                        'visible' => app()->hasModule('gii') && user()->can(Roles::ADMIN),
                    ],
                    [
                        'icon' => 'fa fa-fw fa-plus',
                        'label' => 'Создать модуль',
                        'url' => $this->getMenuUrl('/gii/module'),
                        'visible' => app()->hasModule('gii') && user()->can(Roles::ADMIN),
                    ],
                    [
                        'icon' => 'fa fa-fw fa-plus',
                        'label' => 'Создать контроллер',
                        'url' => $this->getMenuUrl('generator/controller'),
                        'visible' => app()->hasModule('gii') && user()->can(Roles::ADMIN),
                    ],
                    [
                        'label' => '',
                        'itemOptions' => [
                            'role' => 'separator',
                            'class' => 'divider',
                            'visible' => app()->hasModule('gii'),
                        ],
                    ],
                    [
                        'icon' => 'fa fa-fw fa-trash',
                        'label' => 'Очистить кеш',
                        'url' => ['/core/module/flush'],
                        'visible' => user()->can(ModuleTask::OPERATION_EDIT),
                    ],
                ],
                'visible' => user()->can(Roles::ADMIN),
            ]
        ];
    }


}
