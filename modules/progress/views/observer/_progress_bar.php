<?php

use app\modules\progress\models\Observer;
use yii\web\View;

/* @var $this View */
/* @var $title string */
/* @var $sum integer */
/* @var $count integer */

$average = round($sum/$count,2);
?>

<div class="row">
    <div class="col-sm-3 text-center">
        <p><?=$title?></p>
    </div>
    <div class="col-sm-8" data-toggle="tooltip" data-placement="top" title="Средняя оценка успеваемости студентов">
        <div class="progress">
            <div class="progress-bar progress-bar-<?=Observer::getClass($average)?>"
                 role="progressbar" aria-valuenow="<?=round($average)?>" aria-valuemin="0"
                 aria-valuemax="100" style="width: <?=round($average)?>%;">
                <?=$average.'%'?>
            </div>
        </div>
    </div>
    <div class="col-sm-1 text-center" data-toggle="tooltip" data-placement="top" title="Количество студентов, участвующих в статистике">
        <?=$count?>
    </div>
</div>
