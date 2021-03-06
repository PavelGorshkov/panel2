<?php
namespace app\modules\core\assets;

use yii\web\AssetBundle;

/**
 * Class AdminLteAssets
 * @package app\modules\core\assets
 */
class AdminLteAssets extends  AssetBundle {

    public $sourcePath = '@app/modules/core/assets/adminlte';

    public $css = [
        'css/AdminLTE.css',
        'css/skins/_all-skins.min.css',
        'css/style.css'
    ];

    public $js = [
        'js/app.js',
        'js/skins.js',
    ];

    public $depends = [
        'app\modules\core\assets\FontAwesomeAssets',
        'app\modules\core\assets\AppAssets',
        'app\modules\core\assets\IonAssets',
        'app\modules\core\assets\FastClickAssets',
        'app\modules\core\assets\SlimScrollAssets',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}