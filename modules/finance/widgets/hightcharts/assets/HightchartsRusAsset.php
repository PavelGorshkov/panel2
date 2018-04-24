<?php

namespace app\modules\finance\widgets\hightcharts\assets;

use yii\web\AssetBundle;

/**
 * Class HightchartsRusAssets
 * @package app\modules\finance\widgets\hightcharts\assets
 */
class HightchartsRusAsset extends AssetBundle
{
    public $sourcePath = "@app/modules/finance/widgets/hightcharts/assets/js";

    public $js = [
        'lang.js'
    ];

    public $depends = [
        HightchartsAsset::class,
    ];
}