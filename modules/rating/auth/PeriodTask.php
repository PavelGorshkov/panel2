<?php

namespace app\modules\rating\auth;

use app\modules\user\components\RbacItem;
use app\modules\user\components\Roles;
use yii\rbac\Item;


/**
* Задача CRUD Период
*
* Class PeriodTask
* @package app\modules\rating\auth
*/
class PeriodTask extends RbacItem
{
    const TASK = '/rating/period';

    const OPERATION_CREATE = '/rating/period/create';

    const OPERATION_UPDATE = '/rating/period/update';

    const OPERATION_DELETE = '/rating/period/delete';

    const OPERATION_READ = '/rating/period/index';

    public $types = [
        self::TASK => Item::TYPE_ROLE,
        self::OPERATION_READ => Item::TYPE_PERMISSION,
        self::OPERATION_CREATE => Item::TYPE_PERMISSION,
        self::OPERATION_UPDATE => Item::TYPE_PERMISSION,
        self::OPERATION_DELETE => Item::TYPE_PERMISSION,
    ];


    /**
    * @return array
    */
    public function titleList()
    {
        return [
            self::TASK => 'CRUD Период',
            self::OPERATION_READ => 'Просмотр периодов',
            self::OPERATION_CREATE => 'Создание периода',
            self::OPERATION_UPDATE => 'Обновление периода',
            self::OPERATION_DELETE => 'Удаление периода',
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
            ],
        ];
    }
}