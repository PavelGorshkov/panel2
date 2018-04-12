<?php

use app\modules\core\components\View;
use app\modules\core\widgets\BoxWidget;
use app\modules\finance\widgets\daterangepicker\RangeWidget;
use app\modules\finance\widgets\YearWidget;

/* @var $this View */
/* @var $widgetYearData array */
/* @var $widgetRangeData array */

BoxWidget::begin([
    'title' => 'Финансовые поступления',
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
<?php
BoxWidget::end();
