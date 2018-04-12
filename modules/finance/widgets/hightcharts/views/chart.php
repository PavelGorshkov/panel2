<?php
    use app\modules\finance\widgets\hightcharts\assets\{DrilldownAssets, HightchartsRusAssets};
    use app\modules\core\components\View;
    use app\modules\finance\interfaces\FinanceChartInterface;

    /* @var View $this */
    /* @var FinanceChartInterface $model */
    /* @var string $series */
    /* @var string $drilldown */
    /* @var string $tooltip */
    /* @var string $xAxis */
    /* @var string $yAxis */
    /* @var string $id */

    HightchartsRusAssets::register($this);

    if($drilldown){
        DrilldownAssets::register($this);
    }
?>

<div id="<?=$id?>" style="min-width: 310px; width: 100%; height: 400px; margin: 0 auto"></div>

<?php

$this->registerJs(/** @lang text */
    <<<JS
    !function ($) {
        $(function (){
            $('#{$id}').highcharts(
                {
                    chart: {
                        zoomType: 'x',
                        type: '{$model->getType()}',
                        events: {
                            load: function () {
                                // Draw the flow chart
                                var ren = this.renderer;
                                
                                ren.label('{$model->getIndicatorLabel()}', 0, 0, '', 0, 0, true)
                                    .attr({
                                        fill: '#ffffff',
                                        stroke: 'white',
                                        'stroke-width': 2,
                                        padding: 5,
                                        r: 5})
                                    .css({color: 'black'})
                                    .add();
                            }   
                        }
                    },
                    credits: {
                        enabled: false
                    },                    
                    title: {
                        text: '{$model->getChartTitle()}'
                    },                                        
                    yAxis: {$yAxis},
                    xAxis: {$xAxis},
                    tooltip: {$tooltip},                    
                    series: {$series},
                    drilldown: {$drilldown}
                }
            );
         })
     }(window.jQuery)
JS
    , View::POS_LOAD
);


