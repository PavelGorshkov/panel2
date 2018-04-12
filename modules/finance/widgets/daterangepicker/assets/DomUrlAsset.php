<?php

namespace app\modules\finance\widgets\daterangepicker\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Class DomUrlAsset
 * @package app\modules\finance\widgets\daterangepicker\assets
 */
class DomUrlAsset extends AssetBundle
{
    public $sourcePath = '@bower/domurl';

    public $js = [
        'url.js',
    ];

    public $depends = [
        YiiAsset::class,
    ];
}