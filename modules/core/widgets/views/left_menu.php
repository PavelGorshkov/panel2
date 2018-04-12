<?php

use app\modules\core\components\View;
use app\modules\core\widgets\SideBarWidget;

/* @var View $this */


try {
    echo SideBarWidget::widget([
        'items' => $menu
    ]);
} catch (Exception $e) {

    echo $e->getMessage();
}

$this->registerCss(<<<CSS

    
.sidebar-collapse .sidebar-menu > li > a > span:not(.pull-right-container) {

    z-index: 5;
    margin-right: 10px;
}
CSS
);