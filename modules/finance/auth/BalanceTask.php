<?php

namespace app\modules\finance\auth;

use app\modules\user\components\RbacItem;
use app\modules\user\components\Roles;
use yii\rbac\Item;


/**
* Задача Финансовые остатки
*
* Class BalanceTask
* @package app\modules\finance\auth
*/
class BalanceTask extends RbacItem
{
    const TASK = '/finance/balance';

    const OPERATION_GRAPH = '/finance/balance/graph';

    const OPERATION_DETAIL = '/finance/balance/detail';

    const OPERATION_LASTDAY = '/finance/balance/lastday';

    const OPERATION_INDEX = '/finance/balance/index';

    public $types = [
        self::TASK => Item::TYPE_ROLE,
        self::OPERATION_INDEX => Item::TYPE_PERMISSION,
        self::OPERATION_GRAPH => Item::TYPE_PERMISSION,
        self::OPERATION_DETAIL => Item::TYPE_PERMISSION,
        self::OPERATION_LASTDAY => Item::TYPE_PERMISSION,
    ];


    /**
    * @return array
    */
    public function titleList()
    {
        return [
            self::TASK => 'Финансовые остатки',
            self::OPERATION_INDEX => 'Статистика финансовых остатков',
            self::OPERATION_GRAPH => 'График динамики остатков',
            self::OPERATION_DETAIL => 'Детальная информация остатков',
            self::OPERATION_LASTDAY => 'Таблица последнего дня',
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
                self::OPERATION_INDEX,
                self::OPERATION_GRAPH,
                self::OPERATION_DETAIL,
                self::OPERATION_LASTDAY,
            ],
        ];
    }
}