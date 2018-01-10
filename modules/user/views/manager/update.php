<?php
/* @var $this  View */
/* @var $modelForm UserFormModel */
/* @var $model ManagerUser */
/* @var $module Module */

use app\modules\core\components\View;
use app\modules\core\widgets\BoxWidget;
use app\modules\user\forms\UserFormModel;
use app\modules\user\models\ManagerUser;
use app\modules\user\Module;

$this->setSmallTitle($modelForm->email);

BoxWidget::begin([
    'type'=>BoxWidget::TYPE_WARNING,
    'title'=>'Редактирование пользователя'
]);
echo $this->render('_form', [
    'modelForm'=>$modelForm,
    'model'=>$model,
    'module'=>$module,
]);
BoxWidget::end();
