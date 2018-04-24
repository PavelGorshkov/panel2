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

    const OPERATION_READ = '/finance/finance/index';

    public $types = [
        self::TASK => Item::TYPE_ROLE,
        self::OPERATION_READ => Item::TYPE_PERMISSION,
    ];


    /**
    * @return array
    */
    public function titleList()
    {
        return [
            self::TASK => 'Финансовое обеспечение',
            self::OPERATION_READ => 'Просмотр финансовых показателей',
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
            ],
        ];
    }
}