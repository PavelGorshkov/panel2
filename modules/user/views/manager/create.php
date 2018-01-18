<?php
/* @var $this View */
/* @var $model UserFormModel */
/* @var $module Module */

use app\modules\core\components\View;
use app\modules\core\widgets\BoxWidget;
use app\modules\user\forms\UserFormModel;
use app\modules\user\Module;

$this->setSmallTitle($model->email);

BoxWidget::begin([
    'type'=>BoxWidget::TYPE_SUCCESS,
    'title'=>'Создание пользователя'
]);
echo $this->render('_form', [
    'model'=>$model,
    'module'=>$module,
]);
BoxWidget::end();
