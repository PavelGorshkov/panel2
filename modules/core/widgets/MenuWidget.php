<?php
namespace app\modules\core\widgets;


use app\modules\core\helpers\RouterUrlHelper;

class MenuWidget extends Widget {

    public $view = 'menu';

    public $menu;

    public function run() {

        $menu = $this->menu;

        $menuData = isset(app()->menuManager->$menu)?app()->menuManager->$menu:[];

        $this->addPermission($menuData);

        echo $this->render($this->view, ['menu'=>$menuData]);
    }


    protected function addPermission(&$menu) {

        foreach ($menu as &$item) {

            if (!isset($item['visible'])) {

                if (isset($item['url'])) {
                    $item['visible'] = user()->can(RouterUrlHelper::to($item['url']));
                } else {

                    $item['visible'] = true;
                }
            }

            if (isset($item['items'])) {

                $this->addPermission($item['items']);
            }
        }
    }
}