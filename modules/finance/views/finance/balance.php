<?php

use app\modules\core\widgets\BoxWidget;
use app\modules\finance\assets\PivotAssets;
use app\modules\finance\interfaces\FinanceObserverInterface;
    use app\modules\finance\widgets\daterangepicker\RangeWidget;
    use app\modules\finance\widgets\YearWidget;
    use yii\web\View;

    /* @var $this View */
    /* @var $model FinanceObserverInterface */
    /* @var $widgetYearData array */
    /* @var $action string */
?>

<?=YearWidget::widget($widgetYearData);?>
<?=RangeWidget::widget();?>

<!-- Построение графика -->
<?=$this->render('_indicator_chart', ['model' => $model])?>


<!-- Построение pivot -->
<?php if($pivot = $model->getPivotData()): ?>
    <?php
        echo $this->render('_pivot_data');


    $pivot = [
        $pivot[0],
        $pivot[1],
        $pivot[2],
        $pivot[3],
        $pivot[4],
    ];
    printr($pivot);


        $pivot = json_encode($pivot);
        $this->registerJs( ///** @lang text */
<<<JS
    !function ($) {
        $(function() {
                     
            var sum = $.pivotUtilities.aggregatorTemplates.sum();            
            var numberFormat = $.pivotUtilities.numberFormat;
            var float = numberFormat({digitsAfterDecimal: 2});            

            $("#pivotOutput").pivot(
                {$pivot},
                {
                    rows: ["Дата"],
                    cols: ["КВД"],
                    //values: ["Начало", "Конец"],
                    //values: ["Конец"],
                    aggregators: { 
                        //"Начало":  function() { return sum(float)(["Начало"]) },
                        "Конец":  function() { return sum(float)(["Конец"]) }
                    },
                    renderers: $.extend(
                        $.pivotUtilities.renderers,
                        $.pivotUtilities.c3_renderers
                    )
                },
                false,
                'ru'
            );
        })
    }(window.jQuery)
JS
            ,View::POS_LOAD);
    ?>
<?php endif; ?>
