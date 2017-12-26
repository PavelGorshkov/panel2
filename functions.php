<?php

use app\modules\core\components\Application as CoreApplication;
use yii\console\Application as ConsoleApplication;
use yii\web\Application as WebApplication;

function printr($data, $isDie = false) {

        //if (!((defined('YII_ENV') && YII_ENV === 'dev') && (defined('YII_DEBUG') && YII_DEBUG))) return;

        if ($data === null) {

            echo  '<pre>Parameter is null</pre>';
        } else {

            echo '<pre>', print_r($data, 1), '</pre>';
        }

        if ($isDie) die();
    }

    /**
    * @return CoreApplication|ConsoleApplication|WebApplication the application instance
    */
    function app() {

        return Yii::$app;
    }


    /**
     * @return \app\modules\user\components\WebUser
     */
    function user() {

        return app()->user;
    }


    /**
     * @return \yii\caching\CacheInterface
     */
    function cache() {

        return Yii::$app->cache;
    }



    function file_crc32($file) {

        if (!file_exists($file)) return 0;

        return crc32(file_get_contents($file));
    }
