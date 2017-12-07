<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 07.12.2017
 * Time: 14:22
 */

namespace app\modules\core\assets;


use yii\web\AssetBundle;

class sparklineAssets extends AssetBundle {

    public $sourcePath = '@bower/';

    public $css = [];

    public $js = [
        'jquery.sparkline.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}