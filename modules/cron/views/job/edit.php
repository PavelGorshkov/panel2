<?php

/* @var $this  View */
/* @var $model JobScheduleFormModel*/
/* @var $module Module */

use app\modules\core\components\View;
use app\modules\core\widgets\BoxWidget;
use app\modules\cron\forms\JobScheduleFormModel;
use app\modules\user\Module;

$this->setSmallTitle($model->isNewRecord ? 'Создание задания' : 'Редактирование задания');

BoxWidget::begin(['type'=>BoxWidget::TYPE_WARNING, 'title'=>'Редактирование']);

echo $this->render('_form', [
    'model'=>$model,
]);

BoxWidget::end();
