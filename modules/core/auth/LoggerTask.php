<?php

namespace app\modules\core\auth;

use app\modules\user\components\RBACItem;
use app\modules\user\components\Roles;
use yii\rbac\Item;


/**
 * Задача Просмотр лог-файлов
 *
 * Class LoggerTask
 * @package app\modules\core\auth
 */
class LoggerTask extends RBACItem
{
    const TASK = '/core/logger';

    const OPERATION_READ = '/core/logger/index';

    const OPERATION_VIEW = '/core/logger/view';

    const OPERATION_DELETE = '/core/logger/delete';

    public $types = [
        self::TASK => Item::TYPE_ROLE,
        self::OPERATION_VIEW => Item::TYPE_PERMISSION,
        self::OPERATION_READ => Item::TYPE_PERMISSION,
        self::OPERATION_DELETE => Item::TYPE_PERMISSION,
    ];


    /**
     * Возвращает список названий операций
     *
     * @return array
     */
    public function titleList()
    {
        return [
            self::TASK => 'Работа с лог-файлами',
            self::OPERATION_READ => 'Просмотр лог-файлов',
            self::OPERATION_VIEW => 'Подробный просмотр лог-файла',
            self::OPERATION_DELETE => 'Удалить лог-файл',
        ];
    }


    /**
     * @inheritdoc
     *
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
                self::OPERATION_VIEW,
                self::OPERATION_DELETE,
            ],
        ];
    }
}