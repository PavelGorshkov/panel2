<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 21.12.2017
 * Time: 8:49
 */

namespace app\modules\user\helpers;


use app\modules\core\helpers\ListHelper;

class UserTokenTypeHelper extends ListHelper {

    const ACTIVATE = 1;

    const CHANGE_PASSWORD = 2;

    const EMAIL_VERIFY = 3;



    public static function getList()
    {
        return [
            self::ACTIVATE => 'Активация пользователя',
            self::CHANGE_PASSWORD => 'Изменение/сброс пароля',
            self::EMAIL_VERIFY => 'Подтверждение email',
        ];
    }


    public static function getHtmlList() {

        return [
            self::ACTIVATE => '<span class="label label-info">Активация пользователя</span>',
            self::CHANGE_PASSWORD => '<span class="label label-warning">Изменение/сброс пароля</span>',
            self::EMAIL_VERIFY => '<span class="label label-success">Подтверждение email</span>',
        ];
    }
}