<?php
use app\modules\core\components\View;
use app\modules\core\widgets\CalloutWidget;
use app\modules\user\forms\UserFormModel;
use app\modules\user\models\ManagerUser;
use app\modules\user\Module;
use yii\bootstrap\ActiveForm;

/* @var $this  View */
/* @var $modelForm UserFormModel */
/* @var $model ManagerUser */
/* @var $module Module */

$form = ActiveForm::begin([
    'id'=>'manager-user-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
    'validateOnType' => false,
    'validateOnChange' => false,
    'options'=> [
        'enctype' => 'multipart/form-data',
    ]
]);
echo CalloutWidget::widget([
    'message'=>'Поля отмеченные <span class="text-danger">*</span> обязательны для заполнения!',
]);
echo $form->errorSummary($model);



$form->end();
