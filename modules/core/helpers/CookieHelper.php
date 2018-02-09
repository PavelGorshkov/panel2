<?php

namespace app\modules\core\helpers;

use Yii;
use yii\base\BaseObject;
use yii\web\Cookie;

/**
 * Class Cookie
 * @package common\components
 */
class CookieHelper extends BaseObject
{
    /**
     * @param string $cookie
     * @return bool
     */
    public static function has($cookie)
    {
        return Yii::$app->getRequest()->getCookies()->has($cookie);
    }


    /**
     * Получение всех кук
     * @return \yii\web\CookieCollection
     */
    public static function getAll() {

        return Yii::$app->getRequest()->getCookies();
    }


    /**
     * @param string $cookie
     * @param bool $removeFromBrowser
     */
    public static function remove($cookie, $removeFromBrowser = true)
    {
        if (self::has($cookie)) {

            Yii::$app->getResponse()->getCookies()->remove($cookie, $removeFromBrowser);
        }
    }


    /**
     * @param string $cookie
     * @param string|int $value
     * @param int $expire
     * @param bool $httpOnly
     * @param string $domain
     * @param bool $secure
     * @param string $path
     * @throws \yii\base\InvalidConfigException
     */
    public static function set($cookie, $value, $expire = 0, $httpOnly = true, $domain = '', $secure = false, $path = '/')
    {
        $cookieObject = Yii::createObject([
            'class'=>Cookie::className(),
            'name' => $cookie,
            'value' => $value,
            'expire' => $expire,
            'httpOnly' => $httpOnly,
            'domain' => $domain,
            'secure' => $secure,
			'path' => $path,
        ]);

        /** @var Cookie $cookieObject */
        Yii::$app->getResponse()->getCookies()->add($cookieObject);
    }


    /**
     * @param $cookie
     * @param mixed|null $defaultValue
     * @return Cookie
     */
    public static function get($cookie, $defaultValue = null)
    {
        return app()->getRequest()->getCookies()->getValue($cookie, $defaultValue);
    }
}