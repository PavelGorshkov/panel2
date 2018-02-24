<?php
namespace app\modules\core\assets;

use yii\web\AssetBundle;

/**
 * Class fastclickAssets
 * @package app\modules\core\assets
 */
class FastClickAssets extends AssetBundle
{
    public $sourcePath = '@bower/fastclick';

    public $css = [];

    public $js = [
        'lib/fastclick.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}