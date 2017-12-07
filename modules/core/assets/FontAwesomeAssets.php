<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 07.12.2017
 * Time: 13:51
 */

namespace app\modules\core\assets;

use yii\web\AssetBundle;

class FontAwesomeAssets extends  AssetBundle {

    public $sourcePath = '@bower/font-awesome';

    public $css = [
        'css/font-awesome.min.css'
    ];

    public $js = [

    ];

    public $depends = [

    ];
}