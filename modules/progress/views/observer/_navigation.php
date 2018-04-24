<?php

use app\modules\core\widgets\BoxBodyWidget;
use app\modules\progress\models\Observer;
use app\modules\progress\widgets\YearWidget;
use yii\web\View;

/* @var $this View */
/* @var $min integer */
/* @var $max integer */
/* @var $year integer */
/* @var $forms array */
/* @var $model Observer */
?>

<?php BoxBodyWidget::begin();?>

    <div class="row">
        <div class="col-sm-6">
            <?=YearWidget::widget(['minYear' => $min, 'current' => $max, 'maxYear' => $year]);?>
        </div>

        <div class="col-sm-6">
            <?php if(($menuForm = $model->getFormMenuWidget($year)) !== null): ?>
                <?=$menuForm?>
            <?php else: ?>
                <div class="alert alert-warning">Данные за указанный период отсутствуют!</div>
            <?php endif; ?>
        </div>
    </div>

<?php BoxBodyWidget::end();?>
