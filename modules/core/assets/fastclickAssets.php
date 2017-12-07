<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 07.12.2017
 * Time: 14:14
 */

namespace app\modules\core\assets;


use yii\web\AssetBundle;

class fastclickAssets extends AssetBundle
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