<?php
namespace app\modules\user\helpers;

use app\modules\core\helpers\ListHelper;

/**
 * Класс helper для работы со статусами активности пользователя
 *
 * Class UserStatusHelper
 * @package app\modules\user\helpers
 */
class UserStatusHelper extends ListHelper {

    const STATUS_BLOCK = 0;

    const STATUS_ACTIVE = 1;

    const STATUS_NOT_ACTIVE = 2;


    public static function getList()
    {
        return [
            self::STATUS_ACTIVE => 'Активирован',
            self::STATUS_BLOCK => 'Заблокированный',
            self::STATUS_NOT_ACTIVE => 'Не активированный',
        ];
    }


    public static function getHtmlList()
    {
        return [
            self::STATUS_ACTIVE => '<span class="label label-success">Активирован</span>',
            self::STATUS_NOT_ACTIVE => '<span class="label label-warning">Не активированный</span>',
            self::STATUS_BLOCK => '<span class="label label-success">Заблокированный</span>',
        ];
    }
}