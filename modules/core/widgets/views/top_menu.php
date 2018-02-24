<?php

use app\modules\core\widgets\SideBarWidget;

echo SideBarWidget::widget([
    'items' => $menu,
    'options'=>[
        'class'=>'nav navbar-nav',
    ],
    'encodeLabels' => false,
]);