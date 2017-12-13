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

class ModuleTask extends RBACItem {

    const TASK = 'core.module';

    const OPERATION_READ = 'core.module.read';

    const OPERATION_EDIT = 'core.module.edit';

    const OPERATION_SETTINGS = 'core.module.settings';

    public $types = [
        self::TASK => Item::TYPE_ROLE,
        self::OPERATION_EDIT => Item::TYPE_PERMISSION,
        self::OPERATION_READ => Item::TYPE_PERMISSION,
        self::OPERATION_SETTINGS => Item::TYPE_PERMISSION,
    ];


    public function titleList()
    {
        return [
            self::TASK => 'Управление модулями',
            self::OPERATION_SETTINGS => 'Управление настройками модулей',
            self::OPERATION_EDIT => 'Редактирование состояния модуля',
            self::OPERATION_READ => 'Список модулей',
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
                self::OPERATION_EDIT,
                self::OPERATION_READ,
            ]
        ];
    }
}