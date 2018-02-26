<?php

namespace app\modules\user\helpers;

use app\modules\core\helpers\ListHelper;
use app\modules\user\components\Roles;

/**
 * Класс helper для работы с уровнями доступа пользователя
 *
 * Class UserAccessLevel
 * @package app\modules\user\helpers
 */
class UserAccessLevelHelper extends ListHelper
{
    const LEVEL_ADMIN = 1;

    const LEVEL_USER = 0;

    const LEVEL_LDAP = 4;

    const LEVEL_API = 5;


    /**
     * @return array
     */
    public static function getHtmlList()
    {
        return self::getList();
    }


    /**
     * @return array
     */
    public static function getList()
    {
        return [
            self::LEVEL_ADMIN => 'Aдминистраторы',
            self::LEVEL_USER => 'Пользователи',
            self::LEVEL_LDAP => 'LDAP пользователь',
            self::LEVEL_API => 'REST пользователь',
        ];
    }


    /**
     * @return array
     */
    public static function listRoles()
    {
        return [
            self::LEVEL_ADMIN => Roles::ADMIN,
            self::LEVEL_USER => Roles::USER,
            self::LEVEL_LDAP => Roles::LDAP,
        ];
    }
}