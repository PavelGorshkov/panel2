<?php
namespace app\modules\core\assets;

use yii\web\AssetBundle;

/**
 * Class slimScrollAssets
 * @package app\modules\core\assets
 */
class SlimScrollAssets extends AssetBundle {

    public $sourcePath = '@bower/slimscroll';

    public $css = [];

    public $js = [
        'jquery.slimscroll.min.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}