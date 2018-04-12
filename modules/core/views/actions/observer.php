<?php

use app\modules\core\components\View;
use app\modules\core\widgets\BoxWidget;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var View $this */
/* @var array $content */

foreach ($content as $action => $template) {

    $action = str_replace(['/'], ['_'], $action);

    Pjax::begin([
        'id'=>  $action.'Pjax',
        'enablePushState' => true,
        'enableReplaceState' => false,
        'timeout' => 50000,
    ]);
    echo $template['content'];
    Pjax::end();
}
