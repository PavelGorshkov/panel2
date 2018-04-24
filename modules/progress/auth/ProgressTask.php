<?php

namespace app\modules\progress\auth;

use app\modules\user\components\RbacItem;
use app\modules\user\components\Roles;
use yii\rbac\Item;

/**
* Задача Просмотр статистики
*
* Class ProgressTask
* @package app\modules\progress\auth
*/
class ProgressTask extends RbacItem{

    const TASK = '/progress/observer';
    const OPERATION_READ = '/progress/observer/index';

    public $types = [
        self::TASK => Item::TYPE_ROLE,
        self::OPERATION_READ => Item::TYPE_PERMISSION
    ];


    /**
    * @return array
    */
    public function titleList(){
        return [
            self::TASK => 'Просмотр статистики',
            self::OPERATION_READ => 'Просмотр статистики'
        ];
    }


    /**
     * @return array
     */
    public function getTree(){
        return [
            Roles::ADMIN => [
                self::TASK,
            ],
            self::TASK => [
                self::OPERATION_READ
            ],
        ];
    }
}