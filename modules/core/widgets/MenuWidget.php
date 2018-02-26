<?php
namespace app\modules\core\widgets;


use app\modules\core\helpers\RouterUrlHelper;
use yii\base\Widget;

/**
 * Class MenuWidget
 * @package app\modules\core\widgets
 */
class MenuWidget extends Widget {

    public $view = 'menu';

    public $menu;

    /**
     * @inheritdoc
     * @return string|void
     */
    public function run() {

        $menu = $this->menu;

        $menuData = isset(app()->menuManager->$menu)?app()->menuManager->$menu:[];

        $this->addPermission($menuData);

        echo $this->render($this->view, ['menu'=>$menuData]);
    }


    /**
     * @param array $menu
     */
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