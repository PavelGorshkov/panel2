<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 13.12.2017
 * Time: 9:40
 */

namespace app\modules\user\helpers;


class UserAccessLevel {

    const LEVEL_ADMIN = 1;

    const LEVEL_OBSERVER = 2;

    const LEVEL_REDACTOR = 3;

    const LEVEL_USER = 0;

    public static function getList() {

        return [
            self::LEVEL_ADMIN => 'Aдминистратор',
            self::LEVEL_REDACTOR => 'Редактор',
            self::LEVEL_OBSERVER =>  'Наблюдатель',
            self::LEVEL_USER =>  'Пользователь',
        ];
    }
}