<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 07.12.2017
 * Time: 13:26
 */

namespace app\modules\core\assets;

use yii\web\AssetBundle;

class IonAssets extends  AssetBundle {

    public $sourcePath = '@vendor/bower/ionicons-min';

    public $css = [
        'css/ionicons.min.css'
    ];

    public $js = [

    ];

    public $depends = [

    ];
}