<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 13.12.2017
 * Time: 11:31
 */

namespace app\modules\core\auth;

use app\modules\user\components\RBACItem;
use app\modules\user\components\Roles;
use yii\rbac\Item;

/**
 * Задача управления модулями
 *
 * Class ModuleTask
 * @package app\modules\core\auth
 */
class ModuleTask extends RBACItem {

    const TASK = '/core/module';

    const OPERATION_ENABLED = '/core/module/index';

    const OPERATION_DISABLED = '/core/module/disabled';

    const OPERATION_FLUSH = '/core/module/flush';

    const OPERATION_SETTINGS = '/core/module/settings';

    public $types = [
        self::TASK => Item::TYPE_ROLE,
        self::OPERATION_ENABLED => Item::TYPE_PERMISSION,
        self::OPERATION_DISABLED => Item::TYPE_PERMISSION,
        self::OPERATION_FLUSH => Item::TYPE_PERMISSION,
        self::OPERATION_SETTINGS => Item::TYPE_PERMISSION,
    ];


    public function titleList()
    {
        return [
            self::TASK => 'Управление модулями',
            self::OPERATION_SETTINGS => 'Управление настройками модулей',
            self::OPERATION_ENABLED => 'Просмотр списка активных модулей',
            self::OPERATION_DISABLED => 'Просмотр списка неактивных модулей',
            self::OPERATION_FLUSH => 'Очистка кеша системы',
        ];
    }


    public function getTree()
    {
        return [
            Roles::ADMIN => [
                self::TASK,
            ],
            self::TASK => [
                self::OPERATION_SETTINGS,
                self::OPERATION_ENABLED,
                self::OPERATION_DISABLED,
                self::OPERATION_FLUSH,
            ],
        ];
    }
}