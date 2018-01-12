<?php
namespace app\modules\user\helpers;

use app\modules\core\helpers\ListHelper;

/**
 * Класс helper для работы с уровнями доступа пользователя
 *
 * Class UserAccessLevel
 * @package app\modules\user\helpers
 */
class UserAccessLevelHelper extends ListHelper {

    const LEVEL_ADMIN = 1;

    const LEVEL_OBSERVER = 2;

    const LEVEL_REDACTOR = 3;

    const LEVEL_USER = 0;


    /**
     * @return array
     */
    public static function getHtmlList() {

        return self::getList();
    }


    /**
     * @return array
     */
    public static function getList() {

        return [
            self::LEVEL_ADMIN => 'Aдминистраторы',
            self::LEVEL_REDACTOR => 'Редакторы',
            self::LEVEL_OBSERVER =>  'Наблюдатели',
            self::LEVEL_USER =>  'Пользователи',
        ];
    }
}