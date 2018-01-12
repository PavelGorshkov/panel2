<?php
namespace app\modules\core\assets;

use yii\web\AssetBundle;

/**
 * Class iCheckAssets
 * @package app\modules\core\assets
 */
class iCheckAssets extends AssetBundle{

    public $sourcePath = '@bower/icheck';

    public $css = [
        'skins/all.css',
    ];

    public $js = [
        'icheck.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}