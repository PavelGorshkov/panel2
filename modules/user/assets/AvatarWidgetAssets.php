<?php
namespace app\modules\user\assets;

use yii\web\AssetBundle;

/**
 * Class AvatarWidgetAssets
 * @package app\modules\user\assets
 */
class AvatarWidgetAssets extends AssetBundle
{

    public $sourcePath = '@app/modules/user/assets/avatar-widget';

    public $css = [
        'css/image-wrapper.css'
    ];

    public $depends = [
        'app\modules\core\assets\AdminLteAssets',
    ];
}