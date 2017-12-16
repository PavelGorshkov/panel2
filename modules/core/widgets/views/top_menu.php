<?php
use yii\widgets\Menu;

printr($menu, 1);

use app\modules\core\widgets\SideBarWidget;

echo SideBarWidget::widget([
    'items' => $menu,
    'options'=>[
        'class'=>'nav navbar-nav',
    ],
    'encodeLabels' => false,
]);