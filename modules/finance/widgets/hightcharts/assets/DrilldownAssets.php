<?php

namespace app\modules\finance\widgets\hightcharts\assets;

use yii\web\AssetBundle;

/**
 * Class DrilldownAssets
 * @package app\modules\finance\widgets\hightcharts\assets
 */
class DrilldownAssets extends AssetBundle {

    public $sourcePath = '@bower/hightcharts';

    public $js = [
        'modules/data.js',
        'modules/drilldown.js',
    ];

    public $depends = [
        'app\modules\finance\widgets\hightcharts\assets\HightchartsRusAssets'
    ];
}