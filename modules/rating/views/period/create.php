<?php

/** @var $this View */
/** @var $model PeriodForm */
/** @var $module Module */

use app\modules\core\components\View;
use app\modules\core\widgets\BoxWidget;
use app\modules\rating\forms\PeriodForm;
use app\modules\rating\Module;

//$this->setSmallTitle('Title');

BoxWidget::begin([
    'type'=>BoxWidget::TYPE_SUCCESS,
    'title'=>$this->getSmallTitle()
]);
    echo $this->render('_form', [
        'model'=>$model,
        'module'=>$module,
]);
BoxWidget::end();
