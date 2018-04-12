<?php

namespace app\modules\finance\auth;

use app\modules\user\components\RbacItem;
use app\modules\user\components\Roles;
use yii\rbac\Item;

/**
* Задача Финансовое обеспечение
*
* Class FinanceTask
* @package app\modules\finance\auth
*/
class FinanceTask extends RbacItem
{
    const TASK = '/finance/finance';

    const OPERATION_CREATE = '/finance/finance/create';

    const OPERATION_UPDATE = '/finance/finance/update';

    const OPERATION_DELETE = '/finance/finance/delete';

    const OPERATION_READ = '/finance/finance/index';

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
            self::TASK => 'Финансовое обеспечение',
            self::OPERATION_READ => 'Просмотр',
            self::OPERATION_CREATE => '',
            self::OPERATION_UPDATE => '',
            self::OPERATION_DELETE => '',
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