<?php

printr($menu, 1);

use app\modules\core\widgets\SideBarWidget;

echo SideBarWidget::widget([
    'items' => $menu,
    'options'=>[
        'class'=>'nav navbar-nav',
    ],
    'encodeLabels' => false,
]);