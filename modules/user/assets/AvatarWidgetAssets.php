<?php
namespace app\modules\user\assets;

use yii\web\AssetBundle;

class AvatarWidgetAssets extends AssetBundle {

    public $sourcePath = '@app/modules/user/assets/avatar-widget';

    public $css = [
        'css/image-wrapper.css'
    ];

    public $depends = [
        'app\modules\core\assets\AdminLteAssets',
    ];
}