<?php
namespace app\modules\core\assets;

use yii\web\AssetBundle;

class AdminLteAssets extends  AssetBundle {

    public $sourcePath = '@vendor/assets/adminlte';

    public $css = [
        'css/AdminLTE.css',
        'css/skins/_all-skins.min.css',
        'css/style.css'
    ];

    public $js = [
        'js/app.min.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'app\modules\core\assets\AppAssets',
        'app\modules\core\assets\IonAssets',
        'app\modules\core\assets\FontAwesomeAssets',
        'app\modules\core\assets\fastclickAssets',
        'app\modules\core\assets\slimScrollAssets',

    ];

}