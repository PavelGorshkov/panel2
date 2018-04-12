<?php

namespace app\modules\finance\widgets\daterangepicker\assets;

use yii\web\AssetBundle;

/**
 * Class DateRangeAsset
 * @package app\modules\finance\widgets\daterangepicker\assets
 */
class DateRangeAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap-daterangepicker';

    public $js = [
        'moment.js',
        'daterangepicker.js'
    ];

    public $css = [
        'daterangepicker.css',
    ];

    public $depends = [
        DomUrlAsset::class,
    ];
}