<?php

namespace app\modules\user\widgets;


use app\modules\user\helpers\ModuleTrait;
use yii\base\Widget;

/**
 * Class InfoProfileWidget
 * @package app\modules\user\widgets
 */
class InfoProfileWidget extends Widget
{
    use ModuleTrait;

    public $view = 'profile';

    /**
     * @return string
     */
    public function run()
    {
        return $this->render(
            $this->view,
            [
                'user'=>user()->identity,
                'profile'=>user()->profile,
                'module'=>$this->module,
            ]
        );
    }
}