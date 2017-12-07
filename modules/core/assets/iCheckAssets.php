<?php
namespace app\modules\core\assets;

use yii\web\AssetBundle;

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