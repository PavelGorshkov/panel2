<?php

namespace app\modules\user\auth;

use app\modules\user\components\RBACItem;
use app\modules\user\components\Roles;
use yii\rbac\Item;


/**
* Задача Управление ролями
*
* Class RolesTask
* @package app\modules\user\auth
*/
class RolesTask extends RBACItem
{
    const TASK = '/user/roles';

    const OPERATION_CREATE = '/user/roles/create';

    const OPERATION_UPDATE = '/user/roles/update';

    const OPERATION_DELETE = '/user/roles/delete';

    const OPERATION_READ = '/user/roles/index';

    const OPERATION_ACCESS = '/user/roles/access';

    public $types = [
        self::TASK => Item::TYPE_ROLE,
        self::OPERATION_READ => Item::TYPE_PERMISSION,
        self::OPERATION_CREATE => Item::TYPE_PERMISSION,
        self::OPERATION_UPDATE => Item::TYPE_PERMISSION,
        self::OPERATION_DELETE => Item::TYPE_PERMISSION,
        self::OPERATION_ACCESS => Item::TYPE_PERMISSION,
    ];


    /**
    * @return array
    */
    public function titleList()
    {
        return [
            self::TASK => 'Управление пользовательскими ролями',
            self::OPERATION_READ => 'Просмотр пользовательский ролей',
            self::OPERATION_CREATE => 'Создание пользовательской роли',
            self::OPERATION_UPDATE => 'Изменение пользовательской роли',
            self::OPERATION_DELETE => 'Удаление пользовательской роли',
            self::OPERATION_ACCESS => 'Назначение прав',
        ];
    }


    /**
     * @return array
     */
    public function getTree()
    {
        return [
            Roles::ADMIN => [
                self::TASK,
            ],
            self::TASK => [
                self::OPERATION_READ,
                self::OPERATION_CREATE,
                self::OPERATION_UPDATE,
                self::OPERATION_DELETE,
                self::OPERATION_ACCESS,
            ],
        ];
    }
}