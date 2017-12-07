<?php
use yii\widgets\Menu;

echo Menu::widget([
    'items' =>$menu,
    'options'=>[
        'class'=>'nav navbar-nav',
    ],
    'encodeLabels' => false,
]);