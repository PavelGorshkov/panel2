<?php

namespace app\modules\finance\widgets\hightcharts\assets;

use yii\web\AssetBundle;

/**
 * Class HightchartsRusAssets
 * @package app\modules\finance\widgets\hightcharts\assets
 */
class HightchartsRusAssets extends AssetBundle {

    public $sourcePath = "@app/modules/finance/widgets/hightcharts";

    public $js = [
        'js/lang.js'
    ];

    public $depends = [
        'app\modules\finance\widgets\hightcharts\assets\HightchartsAssets'
    ];
}