<?php

/** @var $this View */
/** @var $model RoleFormModel */
/** @var $module Module */

use app\modules\core\components\View;
use app\modules\core\widgets\BoxWidget;
use app\modules\user\forms\RoleFormModel;
use app\modules\user\Module;

$this->setSmallTitle('Добавление роли');

BoxWidget::begin([
    'type'=>BoxWidget::TYPE_SUCCESS,
    'title'=>$this->getSmallTitle()
]);
    echo $this->render('_form', [
        'model'=>$model,
]);
BoxWidget::end();
