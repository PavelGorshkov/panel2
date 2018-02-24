<?php
namespace app\modules\core\assets;

use yii\web\AssetBundle;

/**
 * Class sparklineAssets
 * @package app\modules\core\assets
 */
class SparklineAssets extends AssetBundle {

    public $sourcePath = '@bower/';

    public $css = [];

    public $js = [
        'jquery.sparkline.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}