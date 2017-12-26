<?php

namespace app\modules\user\auth;

use app\modules\user\components\RBACItem;
use app\modules\user\components\Roles;
use yii\rbac\Item;


class ManagerTask extends RBACItem {

    const TASK = '/user/manager';

    const OPERATION_READ = '/user/manager/index';

    const OPERATION_CREATE = '/user/manager/create';

    const OPERATION_UPDATE = '/user/manager/update';

    const OPERATION_DELETE = '/user/manager/delete';


    public $types = [
        self::TASK => Item::TYPE_ROLE,
        self::OPERATION_READ => Item::TYPE_PERMISSION,
        self::OPERATION_CREATE => Item::TYPE_PERMISSION,
        self::OPERATION_UPDATE => Item::TYPE_PERMISSION,
        self::OPERATION_DELETE => Item::TYPE_PERMISSION,
    ];



    public function titleList()
    {
        return [
            self::TASK => 'Управление модулями',
            self::OPERATION_READ => 'Просмотр списков пользователей',
            self::OPERATION_CREATE => 'Создание пользователя',
            self::OPERATION_UPDATE => 'Изменение пользователя',
            self::OPERATION_DELETE => 'Удаление пользователя',
        ];
    }


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
            ],
        ];
    }
}