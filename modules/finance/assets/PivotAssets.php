<?php
namespace app\modules\finance\assets;

use yii\web\AssetBundle;

/**
 * Class PivotAssets
 * @package app\modules\core\assets
 */
class PivotAssets extends  AssetBundle {

    public $sourcePath = '@vendor/nicolaskruchten/pivottable';

    public $css = [
        'dist/pivot.css'
    ];

    public $js = [
        'dist/pivot.js',
        'dist/pivot.ru.js',
    ];

    public $depends = [
        'app\modules\core\assets\AppAssets',
    ];
}