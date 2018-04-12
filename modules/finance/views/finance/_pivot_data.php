<?php
    use app\modules\core\widgets\BoxWidget;
    use app\modules\finance\assets\PivotAssets;
    use yii\web\View;

    /* @var $this View */

    PivotAssets::register($this);
?>

<div class="row">
    <div class="col-sm-12">
        <?php BoxWidget::begin(['type'=>BoxWidget::TYPE_PRIMARY, 'title'=>'Подробная информация']); ?>
            <div id="pivotOutput" class="table-responsive"></div>
        <?php BoxWidget::end(); ?>
    </div>
</div>

<?php
    $this->registerJs(/** @lang text */
<<<JS
    !function ($) {
        $(function() {
            
            var resizer = function () {
                var parentWidth = $("#pivotOutput").parent('.box-body').width(),
                childWidth =  $('.pvtUi').width();

                if(parentWidth <= childWidth){
                    $("#pivotOutput").addClass('showScroll');
                }
                else {
                    $("#pivotOutput").removeClass('showScroll');
                }
            }

            $("#pivotOutput").on('mousedown', '.pvtAttr', function(event) {
                window.setTimeout(resizer, 1000);
            });
        })
    }(window.jQuery)
JS
        ,View::POS_LOAD);

    $this->registerCss(/** @lang text */
<<<CSS
    .showScroll {
        overflow-x: scroll;
    }
CSS
    );


