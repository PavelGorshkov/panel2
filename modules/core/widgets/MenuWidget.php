<?php
namespace app\modules\core\widgets;


class MenuWidget extends Widget {

    public $view = 'menu';

    public $menu;

    public function run() {

        $menu = $this->menu;

        $menuData = [];



        echo $this->render($this->view, ['menu'=>$menuData]);
    }
}