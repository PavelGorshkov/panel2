<?php 

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
    * @return \app\modules\core\components\Application
    */
    function app() {

        return Yii::$app;
    }


    /**
     * @return mixed|\yii\web\User
     */
    function user() {

        return Yii::$app->user;
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
