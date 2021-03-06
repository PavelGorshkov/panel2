<?php
namespace app\modules\developer\auth;

use app\modules\user\components\RbacItem;
use app\modules\user\components\Roles;
use yii\rbac\Item;

/**
 * Задача управление задачами RBAC
 *
 * Class CreateAuthTask
 * @package app\modules\developer\auth
 */
class CreateAuthTask extends RbacItem
{
    const TASK = '/developer/rbac';

    const OPERATION_CREATE = '/developer/rbac/create';

    const OPERATION_READ = '/developer/rbac/index';

    public $types = [
        self::TASK => Item::TYPE_ROLE,
        self::OPERATION_CREATE => Item::TYPE_PERMISSION,
        self::OPERATION_READ => Item::TYPE_PERMISSION

    ];

    /**
     * @return array
     */
    public function titleList()
    {

        return [
            self::TASK => 'Управление задачами RBAC',
            self::OPERATION_CREATE => 'Создание RBAC задачи',
            self::OPERATION_READ => 'Просмотр RBAC задач',
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
            ]
        ];
    }
}
