<?php
use app\modules\core\components\View;
use app\modules\core\widgets\ActiveForm;
use app\modules\user\forms\UserFormModel;
use app\modules\user\helpers\EmailConfirmStatusHelper;
use app\modules\user\helpers\UserStatusHelper;
use app\modules\user\models\User;
use app\modules\user\Module;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\MaskedInput;


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
    <div class="row">
        <div class="col-sm-6">
            <?=$form->field($model, 'access_level')
                ->dropDownList(User::getAccessLevelList())?>
        </div>
        <div class="col-sm-6">
            <?=$form->field($model, 'status')
                ->dropDownList(UserStatusHelper::getList())
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?=$form->field($model, 'about')?>
        </div>
        <div class="col-sm-6">
            <?=$form->field($model, 'phone')
                ->widget(
                    MaskedInput::class,
                    [
                        'mask' => $module->phoneMask,
                        'options'=>[
                            'placeholder'=>$model->getAttributeLabel('phone'),
                            'class'=>'form-control',
                        ]
                    ])
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?=Html::submitButton('Сохранить',
                [
                    'class' => 'btn btn-primary btn-sm',
                    'name' => 'submit-type',
                    'value' => 'index',
                ]
            ); ?>&nbsp;
            <?=Html::submitButton('Применить',
                [
                    'class' => 'btn btn-info btn-sm',
                    'name' => 'submit-type',
                ]
            ); ?>
            <?=Html::a('Отмена', Url::to('index'),
                [
                    'class' => 'btn btn-default btn-sm',
                ]
            ); ?>
        </div>
    </div>
<?php
$form->end();
