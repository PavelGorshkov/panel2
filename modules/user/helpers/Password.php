<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 13.12.2017
 * Time: 9:46
 */

namespace app\modules\user\helpers;

/**
 * Класс helper для работы c паролями
 *
 * Class Password
 * @package app\modules\user\helpers
 */
class Password {

    /**
     * @param $password
     * @return string
     * @throws \yii\base\Exception
     */
    public static function hash($password)
    {
        return app()->security->generatePasswordHash($password, app()->getModule('user')->cost);
    }


    public static function validate($password, $hash)
    {
        return app()->security->validatePassword($password, $hash);
    }


    public static function generate($length)
    {
        $sets = [
            'abcdefghjkmnpqrstuvwxyz',
            'ABCDEFGHJKMNPQRSTUVWXYZ',
            '23456789',
        ];
        $all = '';
        $password = '';
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }

        $all = str_split($all);
        for ($i = 0; $i < $length - count($sets); $i++) {
            $password .= $all[array_rand($all)];
        }

        $password = str_shuffle($password);

        return $password;
    }

}