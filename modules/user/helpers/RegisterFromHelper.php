<?php

namespace app\modules\user\helpers;

use app\modules\core\helpers\ListHelper;
use app\modules\user\interfaces\RegisterFromInterface;

/**
 * Class RegisterFromHelper
 * @package app\modules\user\helpers
 */
class RegisterFromHelper extends ListHelper
{
    const FORM = 0;

    const LDAP = 1;

    /**
     * @return array
     */
    public static function getList()
    {
        return [
            self::FORM => 'Форма регистрации',
            self::LDAP => 'LDAP',
        ];
    }


    /**
     * @return array
     */
    public static function getHtmlList()
    {
        return [
            self::FORM => '<span class="label label-success">Форма регистрации</span>',
            self::LDAP => '<span class="label label-warning">LDAP авторизация</span>',
        ];
    }


    /**
     * @param RegisterFromInterface $user
     * @return bool
     */
    public static function isLdap(RegisterFromInterface $user)
    {
        return $user->getRegisterFrom() === self::LDAP;
    }
}