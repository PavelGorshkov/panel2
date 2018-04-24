<?php

namespace app\modules\finance\widgets\hightcharts\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Class HightchartsAssets
 * @package app\modules\finance\widgets\hightcharts\assets
 */
class HightchartsAsset extends AssetBundle {

    public $sourcePath = '@bower/hightcharts';

    public $css = [
        'css/highcharts.css'
    ];

    public $js = [
        'highcharts.js',
        'highcharts-more.js',
        'modules/exporting.js'
    ];

    public $depends = [
        YiiAsset::class,
    ];
}