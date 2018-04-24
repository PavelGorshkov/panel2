<?php
    use app\modules\core\widgets\BoxWidget;
    use app\modules\finance\interfaces\FinanceObserverInterface;
    use app\modules\finance\widgets\daterangepicker\RangeWidget;
    use app\modules\finance\widgets\hightcharts\FinChartWidget;
    use app\modules\finance\widgets\YearWidget;
    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\web\View;

    /* @var $this View */
    /* @var $model FinanceObserverInterface */
    /* @var $widgetYearData array */
    /* @var $action string */
?>

<div class="row">
    <div class="col-sm-12">
        <?php BoxWidget::begin(['type'=>BoxWidget::TYPE_PRIMARY, 'title'=>$model->getTitle()]); ?>

            <?=YearWidget::widget($widgetYearData);?>
            <?=RangeWidget::widget();?>

            <?php if($data = FinChartWidget::widget(['model'=>$model])): ?>

                <h4>Динамика изменения с <?=$model->getActualStart();?> по <?=$model->getActualFinish();?></h4>

                <?=$data?>

                <!-- Построение таблицы -->
                <?=$this->render('_chart_table', ['model' => $model])?>

                <div class="box-footer">
                    <span class="pull-right">
                        <?= Html::a('Подробнее', Url::to([$action])) ?>
                    </span>
                </div>

            <?php else: ?>
                <div class="alert alert-warning">Данные за указанный период отсутствуют!</div>
            <?php endif; ?>

        <?php BoxWidget::end(); ?>
    </div>
</div>
