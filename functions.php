<?php 

    function printr($data, $isDie = false) {

        if (!((defined('YII_ENV') && YII_ENV === 'dev') && (defined('YII_DEBUG') && YII_DEBUG))) return;

        echo '<pre>',print_r($data, 1),'</pre>';

        if ($isDie) die();
    }

    /**
     * @return \yii\console\Application|\yii\web\Application
     */
    function app() {

        return Yii::$app;
    }
