<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 05.12.2017
 * Time: 13:41
 */

namespace app\helpers;


use Yii;

class ModuleList {

    private static $_excluded = [
        'gii'=>true,
        'debug'=>true,
    ];

    public static function getModules($sorted = true)
    {
        $modules = [];

        foreach (Yii::$app->getModules() as $module => $obj) {

            if (!isset(self::$_excluded[$module])) {

                $modules[$module] = $obj;
            }
        }

        printr($modules);
        printr(Yii::$app, 1);

        if ($sorted) {
            ksort($modules);
        }

        return $modules;
    }
}