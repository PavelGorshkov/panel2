<?php

namespace app\modules\user\widgets;

use app\modules\user\helpers\ModuleTrait;
use yii\base\Widget;

/**
 * Class InfoNavMenu
 * @package app\modules\user\widgets
 */
class InfoNavMenu extends Widget
{
    use ModuleTrait;

    public $view = 'info_nav_menu';

    /**
     * @return string
     */
    public function run()
    {
        return $this->render($this->view, [
            'user'=>user()->identity,
            'profile'=>user()->profile,
            'module'=>$this->module,
        ]);
    }


}