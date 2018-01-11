<?php
use app\modules\core\components\View;
use app\modules\core\widgets\ActiveForm;
use app\modules\user\forms\UserFormModel;
use app\modules\user\helpers\EmailConfirmStatusHelper;
use app\modules\user\Module;


/* @var $this  View */
/* @var $model UserFormModel */
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

echo $form->errorSummary($model);
?>
    <div class="row">
        <div class="col-sm-6">
            <?=$form->field($model, 'username')?>
        </div>
        <div class="col-sm-6">
            <?=$form->field($model, 'email')->input('email');?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?=$form->field($model, 'full_name')?>
        </div>
        <div class="col-sm-6">
            <?=$form->field($model, 'email_confirm')
                ->dropDownList(EmailConfirmStatusHelper::getList());?>
        </div>
    </div>
<?php
$form->end();
