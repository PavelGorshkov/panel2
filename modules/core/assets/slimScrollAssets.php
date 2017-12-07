<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 07.12.2017
 * Time: 14:34
 */

namespace app\modules\core\assets;


use yii\web\AssetBundle;

class slimScrollAssets extends AssetBundle {

    public $sourcePath = '@bower/slimScroll';

    public $css = [];

    public $js = [
        'jquery.slimscroll.min.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}