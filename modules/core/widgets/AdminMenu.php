<?php
namespace app\modules\core\widgets;

use app\modules\core\helpers\RouterUrlHelper;
use yii\widgets\Menu;

/**
 * Class AdminMenu
 * @package app\modules\core\widgets
 */
class AdminMenu extends Widget {

    public $menu;

    /**
     * @return string|void
     * @throws \Exception
     */
    public function run() {

        $menu = $this->menu;

        $menuData = isset(app()->menuManager->$menu)?app()->menuManager->$menu:[];

        $this->addPermission($menuData);

        $menuData = $this->transformMenu($menuData);

        echo Menu::widget([
            'items'=>$menuData,
            'options'=>['class'=>'nav navbar-nav'],
            'encodeLabels'=>false,
        ]);
    }


    /**
     * @param array $menu
     * @return array
     */
    protected function transformMenu($menu) {

        $transform = [];

        foreach ($menu as $item) {

            $transformItem = [
                'visible'=>$item['visible'],
                'url'=>isset($item['url'])?$item['url']:'#',
            ];

            $transformItem['label'] = isset($item['icon'])?$this->setIcon($item):$item['label'];

            if (isset($item['items'])) {

                $transformItem['options'] = ['class'=>'dropdown'];
                $transformItem['template'] = /** @lang text */
                    '<a class="dropdown-toggle" data-toggle="dropdown" href="{url}">{label}<span class="caret"></span></a>';
                $transformItem['submenuTemplate'] = "<ul class='dropdown-menu'>{items}</ul>";

                $transformItem['items'] = $this->transformSubMenu($item['items']);
            }

            $transform[] = $transformItem;
        }

        return $transform;
    }


    /**
     * @param array $item
     * @return string
     */
    protected function setIcon($item) {

        if (strpos($item['icon'], 'icon') === false && strpos($item['icon'], 'fa') === false) {
            $item['icon'] = 'glyphicon glyphicon-' . implode(' glyphicon-', explode(' ', $item['icon']));
            $label = "<span class='" . $item['icon'] . "'></span>" . $item['label'];
        } else {
            $label = "<i class='" . $item['icon'] . "'></i>" . $item['label'];
        }

        return $label;
    }


    /**
     * @param array $items
     * @return array
     */
    protected function transformSubMenu(&$items) {

        $subMenu = [];
        foreach ($items as $item) {

            $subMenuItem = [
                'visible'=>$item['visible'],
                'label' => $item['label'],
            ];

            if (!isset($item['url'])) {

                if (!isset($item['items'])) {

                    if (trim($item['label'])) {

                        $subMenuItem['options'] = ['class'=>'nav-header'];
                        $subMenuItem['label'] = isset($item['icon'])?$this->setIcon($item):$item['label'];

                    } else {

                        $subMenuItem = $item;
                    }
                    $subMenuItem['url'] = '#';

                } else {

                    $subMenuItem['options'] = ['class'=>'dropdowns'];
                    $subMenuItem['template'] = /** @lang text */
                        '<a class="dropdown-toggle" data-toggle="dropdown" href="{url}">{label}<span class="caret"></span></a>';
                    $subMenuItem['submenuTemplate'] = '"\n<ul class="dropdown-menu">\n{items}\n</ul>\n"';

                    $subMenuItem['items'] = $this->transformSubMenu($item['items']);
                }
            } else {

                $subMenuItem['label'] = isset($item['icon'])?$this->setIcon($item):$item['label'];
                $subMenuItem['url'] = $item['url'];
            }

            $subMenu[] = $subMenuItem;
        }

        return $subMenu;
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