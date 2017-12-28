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
            self::STATUS_ACTIVE => '<i class="fa fa-fw text-success fa-user" title="Активированный"></i>',
            self::STATUS_NOT_ACTIVE => '<i class="fa fa-fw fa-user text-warning" title="Не активированный"></i>',
            self::STATUS_BLOCK => '<i class="fa fa-fw fa-ban text-danger" title="Заблокированный"></i>',
        ];
    }
}