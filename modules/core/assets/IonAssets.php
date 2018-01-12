<?php
namespace app\modules\core\assets;

use yii\web\AssetBundle;

/**
 * Class IonAssets
 * @package app\modules\core\assets
 */
class IonAssets extends  AssetBundle {

    public $sourcePath = '@bower/ionicons-min';

    public $css = [
        'css/ionicons.min.css'
    ];

    public $js = [

    ];

    public $depends = [

    ];
}