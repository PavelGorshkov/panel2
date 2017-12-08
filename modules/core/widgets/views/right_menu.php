<?php

use yii\widgets\Menu;

echo Menu::widget([
    'items' =>$menu,
    'options'=>[
        'class'=>'right-menu control-sidebar-menu',
    ],
    'encodeLabels' => false,
]);