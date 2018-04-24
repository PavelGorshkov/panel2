<?php

use app\modules\core\components\View;
use app\modules\core\widgets\BoxBodyWidget;
use app\modules\finance\models\BalanceGraph;
use app\modules\finance\widgets\daterangepicker\RangeWidget;
use app\modules\finance\widgets\hightcharts\FinChartWidget;
use app\modules\finance\widgets\YearWidget;
use yii\helpers\Html;

/* @var $this View */
/* @var $widgetYearData array */
/* @var $widgetRangeData array */
/* @var $model BalanceGraph */

BoxBodyWidget::begin([
    'title' => 'Финансовые остатки',
    'boxTools' => [Html::a('Подробнее', ['balance/detail'])],
]);

?>
    <div class="row">
        <div class="col-xs-9">
            <?= YearWidget::widget($widgetYearData); ?>
        </div>
        <div class="col-xs-3">
            <?= RangeWidget::widget($widgetRangeData); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?php $data = FinChartWidget::widget([
                'model' => $model,
            ]);

            if (!empty($data)):
                ?>
                <h4>Динамика изменения с <?= $model->getActualStart(); ?> по <?= $model->getActualFinish(); ?></h4>
                <?= $data ?>
                <br/>
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <?php foreach ($model->getTableData() as $row): ?>
                            <td class="<?= $row['class'] ?>"><?= $row['label'] ?></td>
                        <?php endforeach ?>
                    </tr>
                    </tbody>
                </table>
            <?php
            endif;
            ?>
        </div>
    </div>
<?php
BoxBodyWidget::end();
