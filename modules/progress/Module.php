<?php
namespace app\modules\progress;

use app\modules\core\components\Module as ParentModule;

/**
 * progress module definition class
 * Class Module
 * @package app\modules\progress */
class Module extends ParentModule{

    /**
    * @return string
    */
    public static function Title() {
        return 'Успеваемость';
    }


    /**
     * @return array
     */
    public function getMenuMain(){
        return [
            [
                'icon'=>'glyphicon glyphicon-list-alt',
                'label'=>self::Title(),
                'url'=>['/progress/observer/index'],
            ],
        ];
    }
}
