<?php
namespace app\modules\core\helpers;

use app\modules\core\components\Module;

/**
 * Класс helper для получения из url
 * текущий роутинг module/controller/action
 *
 * Class RouterUrlHelper
 * @package app\modules\core\helpers
 */
class RouterUrlHelper
{
    /**
     * @param string $url
     * @return array
     */
    protected static function parseUrl($url)
    {
        if (is_array($url)) {

            $url = array_shift($url);
        }

        return explode('/', ltrim($url, '/'));
    }

    
    /**
     * Получение роутинга из URL
     * @param array|string|mixed $url
     *
     * @return null|string
     */
    public static function to($url)
    {
        $route = self::parseUrl($url);

        $controller = app()->controller;

        $m = $controller->module->id;
        $c = $controller->id;
        $a = $controller->action->id;

        if (empty($route)) {

            return '/' . implode('/', [$m, $c, $a]);
        }

        switch (count($route)) {

            case 3:
                break;

            case 2:

                if ($controller->module instanceof Module) {

                    array_unshift($route, $m);
                }
                break;

            case 1:

                array_unshift($route, $c);
                if ($controller->module instanceof Module) {

                    array_unshift($route, $m);
                }
                break;

            default:
                return null;
        }

        return '/' . implode('/', $route);
    }


    /**
     * Определение активности URL
     *
     * @param $url
     * @return array|bool|null
     */
    public static function isActiveRoute($url)
    {
        $route = self::parseUrl($url);

        if (empty($route)) return true;

        $controller = app()->controller;
        $m = $controller->module->id;
        $c = $controller->id;
        $a = $controller->action->id;

        switch (count($route)) {

            case 3:

                return $route == [$m, $c, $a];

            case 2:

                return $route = [$c, $a];

            case 1:

                return $route == [$a];

            default:

                return null;
        }
    }


    /**
     * @param string $url
     * @return string|null
     */
    public static function getAction($url)
    {
        $url = self::parseUrl($url);

        return !empty($url) ? $url[count($url) - 1] : null;
    }
}