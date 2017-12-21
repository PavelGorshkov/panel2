<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 21.12.2017
 * Time: 8:53
 */

namespace app\modules\user\helpers;

use app\modules\core\helpers\ListHelper;


class UserTokenStatusHelper extends ListHelper{

    const STATUS_NEW = 0;

    const STATUS_ACTIVATE = 1;

    const STATUS_FAIL = 2;


    public static function getList()
    {
        return [
            self::STATUS_NEW => 'Новый',
            self::STATUS_ACTIVATE => 'Активирован',
            self::STATUS_FAIL => 'Скомпроментирован',

        ];
    }


    public static function getHtmlList() {

        return [
            self::STATUS_NEW => '<span class="label label-new">Новый</span>',
            self::STATUS_ACTIVATE => '<span class="label label-info">Активирован</span>',
            self::STATUS_FAIL => '<span class="label label-danger">Скомпроментирован</span>',
        ];
    }
}