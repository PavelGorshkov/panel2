<?php
/* @var $this app\modules\core\components\View */
/* @var $model app\modules\user\models\RecoveryForm */

use app\modules\core\components\View;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Button;
use yii\captcha\Captcha;
use yii\helpers\Html;

$this->setTitle('Восстановление пароля');

?>
<p class="login-box-msg">Восстановление пароля</p>
<?php
    $form = ActiveForm::begin([
        'id'=>'recovery-form',
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
        'validateOnBlur' => false,
        'validateOnType' => false,
        'validateOnChange' => false,
        'options'=> [
            'class'=>'form-signin form'
        ]
    ]);
    $form->errorSummary($model);
?>
<div class='row'>
    <div class="col-sm-12">
        <?= $form->field($model, 'email', [
            'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon"><span class="glyphicon glyphicon-envelope form-control-feedback"></span></span></div>',
            'enableLabel'=>false,
            'inputOptions'=>[
                'autofocus' => 'autofocus',
                'placeholder'=>$model->getAttributeLabel('email'),
                'tabindex'=>'1',
            ],
        ])->input('email'); ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <?=Html::submitButton('Отправить',
            [
                'class' => 'btn btn-primary btn-block btn-lg',
                'tabindex'=>'9',
            ]
        ); ?>
    </div>
</div>
<br/>
<?php ActiveForm::end();?>
<?=Html::a('Вернуться', ['/login'])?>