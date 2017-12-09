<?php
namespace app\modules\core;

use yii\helpers\ArrayHelper;

class Module extends \app\modules\core\components\Module {

    public $uploadPath = 'uploads';

    public $imageUploadPath = 'images';

    public $coreCacheTime = 3600;

    public $copyright = 'ФГБОУ ВО "Марийский государственный универитет"';

    public static function Title() {

        return 'Ядро приложения';
    }


    public function getMenuAdmin() {

        $items = [];

        $items = ArrayHelper::merge($items,
        [
            [
                'label' => 'Модули',
                'visible' => true, //user()->checkAccess(TaskModule::TASK)
            ],
            [
                'icon' => 'fa fa-fw fa-list-alt',
                'label' => 'Список модулей',
                'url' => $this->getMenuUrl('module/index'),
                'visible' => true,//user()->checkAccess(TaskModule::OPERATION_READ)
            ],
        ]);
    }
}
