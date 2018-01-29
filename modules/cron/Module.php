<?php

namespace app\modules\cron;

use app\modules\core\components\Module as ParentModule;
use app\modules\user\components\Roles;

/**
 * cron module definition class
 */
class Module extends ParentModule{

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\cron\controllers';


    /**
     * @inheritdoc
     */
    public function init(){
        parent::init();
        // custom initialization code goes here
    }


    /**
     * Обязательный
     * @return string
     */
    public static function Title(){
        return 'Планировщик заданий';
    }


    /**
     * @return array
     */
    public function getMenuAdmin() {
        return [
            [
                'label'=>'<span class="hidden-xs">Планировщик</span>',
                'icon'=>'fa fa-fw fa-clock-o',
                'items'=>[
                    [
                        'icon' => 'fa fa-fw fa-calendar-o',
                        'label' => 'Управление заданиями',
                        'url' => $this->getMenuUrl('job/index'),
                        'visible'=>true
                    ],
                ],
                'visible' => app()->user->can([Roles::ADMIN]),
            ]
        ];
    }

}
