<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 15.12.2017
 * Time: 14:21
 */

namespace app\modules\core\helpers;

use yii\helpers\Url;

class RouterUrlHelper {

    protected static function parseUrl($url){

        if (is_array($url)) {

            $url = array_shift($url);
        }

        return explode('/', ltrim($url, '/'));
    }


    public static function to($url) {

        $route = self::parseUrl($url);

        $controller = app()->controller;
        $m = $controller->module->id;
        $c = $controller->id;
        $a = $controller->action->id;

        if (empty($route)) {

            return '/'.implode('/', [$m, $c, $a]);
        }

        switch (count($route)) {

            case 3:
                break;

            case 2:

                if ($m !== app()->id) {

                    array_unshift($route, $m);
                }
                break;

            case 1:

                array_unshift($route, $c);
                if ($m !== app()->id) {

                    array_unshift($route, $m);
                }
                break;

            default:return null;
        }

        return '/'.implode('/', $route);
    }


    public static function isActiveRoute($url) {

        $route = self::parseUrl($url);

        if (empty($route)) return true;

        $controller = app()->controller;
        $m = $controller->module->id;
        $c = $controller->id;
        $a = $controller->action->id;

        switch(count($route)) {

            case 3:

                return $route == [$m, $c, $a];

            case 2:

                return $route = [$c, $a];

            case 1:

                return $route == [$a];

            default:

                return null;
        }

        return false;
    }


}