<?php
namespace app\modules\core\widgets;


class MenuWidget extends Widget {

    public $view = 'menu';

    public $menu;

    public function run() {

        $menu = $this->menu;

        $menuData = [];

        if ($menu == 'main') {

            $developerMenuItems = [];
            $menuItems = [];
            $developerMenuItems[] = [
                'url' => ['/sub/action/one'],
                'icon' => 'cog',
                'label' => 'Sub 1',
            ];
            $developerMenuItems[] = [
                'icon' => 'cog',
                'label' => 'No Link',
            ];
            $developerMenuItems[] = [
                'icon' => 'cog',
                'label' => 'Not visible',
                'visible' => false,
            ];
            $developerMenuItems[] = [
                'icon' => 'cog',
                'label' => 'Folder',
                'items' => [
                    [
                        'url' => ['/sub/action/two'],
                        'icon' => 'cog',
                        'label' => 'SubSub 2',
                    ],
                ],
            ];
            $developerMenuItems[] = [
                'url' => ['/sub/action/three'],
                'icon' => 'cog',
                'label' => 'Sub 3',
            ];
            $developerMenuItems[] = [
                'url' => ['/sub/action/param', 'id' => 'a'],
                'icon' => 'cog',
                'label' => 'Param A',
            ];
            $developerMenuItems[] = [
                'url' => ['/sub/action/param', 'id' => 'b'],
                'icon' => 'cog',
                'label' => 'Param B',
            ];

            $menuItems[] = [
                'url' => ['/test'],
                'icon' => 'cog',
                'label' => 'Test',
            ];
            $menuItems[] = [
                #'url' => '#',
                'icon' => 'cog',
                'label' => 'Test with items',
                'items' => $developerMenuItems,
            ];
            for ($i = 0; $i < 25; $i++) {
                $menuItems[] = [
                    'url' => ['/test/auto', 'id' => $i],
                    'icon' => 'cog',
                    'label' => 'Auto ' . $i,
                ];
            }

            $menuData = $menuItems;

        }

        echo $this->render($this->view, ['menu'=>$menuData]);
    }
}