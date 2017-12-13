<?php
namespace app\modules\developer\auth;

use app\modules\user\components\RBACItem;
use app\modules\user\components\Roles;
use yii\rbac\Item;


class CreateAuthTask extends RBACItem
{

    const TASK = 'developer.CreateItem';

    const OPERATION_CREATE = 'developer.CreateItem.create';

    const OPERATION_READ = 'developer.CreateItem.read';

    public $types = [
        self::TASK => Item::TYPE_ROLE,
        self::OPERATION_CREATE => Item::TYPE_PERMISSION,
        self::OPERATION_READ => Item::TYPE_PERMISSION

    ];

    public function titleList()
    {

        return [
            self::TASK => 'Управление задачами RBAC',
            self::OPERATION_CREATE => 'Создание RBAC задачи',
            self::OPERATION_READ => 'Просмотр RBAC задач',
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
            ]
        ];
    }

    public function getTitleTask()
    {
        return self::TASK;
    }
}