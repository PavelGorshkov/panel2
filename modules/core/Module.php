<?php
namespace app\modules\core;

class Module extends \app\modules\core\components\Module {

    public $uploadPath = 'uploads';

    public $imageUploadPath = 'images';

    public $coreCacheTime = 3600;

    public static function Title() {

        return 'Ядро приложения';
    }
}
