<?php
namespace app\modules\core\widgets;

use app\modules\core\helpers\RouterUrlHelper;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

/**
 * Class ShortCutMenuWidget
 * @package app\modules\core\widgets
 */
class ShortCutMenuWidget extends Widget {

    public $menu;

    public $title;

    public $view = 'short_menu';


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


    /**
     * @param array $menu
     * @return array
     */
    protected function scanMenu($menu) {

        $newMenu = [];

        foreach ($menu as $m) {

            if (isset($m['items'])) $newMenu = ArrayHelper::merge($newMenu, $this->scanMenu($m['items']));

            if (!$m['visible']) continue;

            if (!isset($m['url'])) continue;

            preg_match('/(<i.*?>.*?<\/i.*?>)(.*)/si', $m['label'], $label);

            $icon = isset($label[1])?$label[1]:$m['icon'];
            $label = isset($label[2])&&trim($label[2])?$label[2]:$m['label'];

            preg_match('/(<div.*?>(.*)?<\/div.*?>)/si', $label, $label1);

            $label = isset($label1[2])?trim($label1[2]):$label;

            preg_match('/(<h.*?>.*?<\/h.*?>)/si', $label, $label1);

            $label = isset($label1[0])?trim($label1[0]):$label;

            $m['label'] = $this->setLabel($icon, $label);
            $newMenu[] = $m;
        }

        return $newMenu;
    }

    /**
     * @param string $icon
     * @param string $label
     * @return string
     */
    protected function setLabel($icon, $label) {

        if (strpos($icon, 'icon') === false && strpos($icon, 'fa') === false) {

            $icon = 'glyphicon glyphicon-' . implode(' glyphicon-', explode(' ', $icon));
            $label = "<span class='" . $icon . "'></span>" . '<span>'.$label.'</span>';

        } else {
            $label = "<i class='" . $icon . "'></i>" . '<span>'.$label.'</span>';
        }

        return $label;
    }


    /**
     * @return string
     */
    public function run() {

        $this->addPermission($this->menu);

        $menu = $this->scanMenu($this->menu);

        return $this->render($this->view, [
            'menu'=>$menu,
            'title'=>$this->title,
        ]);
    }
}