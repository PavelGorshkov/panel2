<?php
    use app\modules\core\widgets\BoxWidget;
    use app\modules\finance\interfaces\FinanceObserverInterface;
    use app\modules\finance\widgets\hightcharts\FinChartWidget;
    use yii\web\View;

    /* @var $this View */
    /* @var $model FinanceObserverInterface */
?>

<div class="row">
    <div class="col-sm-12">
        <?php BoxWidget::begin(['type'=>BoxWidget::TYPE_PRIMARY, 'title'=>$model->getTitle()]); ?>

        <?php if($data = FinChartWidget::widget(['model'=>$model])): ?>

            <h4>Динамика изменения с <?=$model->getActualStart();?> по <?=$model->getActualFinish();?></h4>

            <?=$data?>

            <!-- Построение таблицы -->
            <?=$this->render('_chart_table', ['model' => $model])?>

        <?php else: ?>
            <div class="alert alert-warning">Данные за указанный период отсутствуют!</div>
        <?php endif; ?>

        <?php BoxWidget::end(); ?>
    </div>
</div>
