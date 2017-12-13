<?php
namespace app\modules\developer\auth;

use app\modules\user\components\RBACItem;
use app\modules\user\components\Roles;
use yii\rbac\Item;

class MigrationTask extends RBACItem {

    const TASK = 'developer.migration';

    const OPERATION_CREATE = 'developer.migration.create';

    const OPERATION_READ = 'developer.migration.read';

    const OPERATION_REFRESH = 'developer.migration.read';


    public $types = [
        self::TASK => Item::TYPE_ROLE,
        self::OPERATION_CREATE => Item::TYPE_PERMISSION,
        self::OPERATION_READ => Item::TYPE_PERMISSION,
        self::OPERATION_REFRESH => Item::TYPE_PERMISSION,

    ];


    public function titleList()
    {
        return [
            self::TASK => 'Управление миграциями',
            self::OPERATION_EDIT => 'Создание миграции',
            self::OPERATION_READ => 'Просмотр списка миграций',
            self::OPERATION_REFRESH => 'Обновление БД',
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
                self::OPERATION_REFRESH,
            ]
        ];
    }
}