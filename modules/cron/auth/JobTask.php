<?php

namespace app\modules\cron\auth;

use app\modules\user\components\RBACItem;
use app\modules\user\components\Roles;
use yii\rbac\Item;

/**
 * Class JobTask
 * @package app\modules\cron\auth
 */
class JobTask extends RBACItem {

    const TASK = '/cron/job';

    const OPERATION_READ = '/cron/job/index';
    const OPERATION_CREATE = '/cron/job/create';
    const OPERATION_UPDATE = '/cron/job/update';
    const OPERATION_DELETE = '/cron/job/delete';
    const OPERATION_RUN = '/cron/job/run';

    public $types = [
        self::TASK => Item::TYPE_ROLE,
        self::OPERATION_READ => Item::TYPE_PERMISSION,
        self::OPERATION_CREATE => Item::TYPE_PERMISSION,
        self::OPERATION_UPDATE => Item::TYPE_PERMISSION,
        self::OPERATION_DELETE => Item::TYPE_PERMISSION,
        self::OPERATION_RUN => Item::TYPE_PERMISSION
    ];


    /**
     * @return array
     */
    public function titleList(){
        return [
            self::TASK => 'Управление заданиями',
            self::OPERATION_READ => 'Просмотр списков задач',
            self::OPERATION_CREATE => 'Создание задачи',
            self::OPERATION_UPDATE => 'Изменение задачи',
            self::OPERATION_DELETE => 'Удаление задачи',
            self::OPERATION_RUN => 'Запуск задачи'
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
                self::OPERATION_READ,
                self::OPERATION_CREATE,
                self::OPERATION_UPDATE,
                self::OPERATION_DELETE,
                self::OPERATION_RUN
            ],
        ];
    }
}