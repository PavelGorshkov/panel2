<?php
namespace app\modules\user\helpers;

use app\modules\core\helpers\ListHelper;

class RegisterFromHelper extends ListHelper {

    const FORM = 0;

    const LDAP = 1;

    public static function getList()
    {
        return [
            self::FORM => 'Форма регистрации',
            self::LDAP => 'LDAP',
        ];
    }


    public static function getHtmlList()
    {
        return [
            self::FORM => '<span class="label label-success">Форма регистрации</span>',
            self::LDAP => '<span class="label label-warning">LDAP авторизация</span>',
        ];
    }
}