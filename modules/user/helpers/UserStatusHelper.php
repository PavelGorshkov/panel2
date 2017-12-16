<?php
namespace app\modules\user\helpers;

use app\modules\core\helpers\StatusHelper;

class UserStatusHelper extends StatusHelper {

    const STATUS_BLOCK = 0;

    const STATUS_ACTIVE = 1;

    const STATUS_NOT_ACTIVE = 2;


    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => 'Активирован',
            self::STATUS_BLOCK => 'Заблокированный',
            self::STATUS_NOT_ACTIVE => 'Не активированный',
        ];
    }


    public static function getStatusHtmlList()
    {
        return [
            self::STATUS_ACTIVE => '<span class="label label-success">Активирован</span>',
            self::STATUS_NOT_ACTIVE => '<span class="label label-warning">Не активированный</span>',
            self::STATUS_BLOCK => '<span class="label label-success">Заблокированный</span>',
        ];
    }
}