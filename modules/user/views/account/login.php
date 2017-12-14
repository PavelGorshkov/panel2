<?php
/* @var $this app\modules\core\components\View */
/* @var $model app\modules\user\models\LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->setTitle('Аутентификация');

?>
<p class="login-box-msg">Для входа в систему<br />введите логин и пароль</p>
<?php
    $form = ActiveForm::begin([
        'id'=>'login-form',
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
    <div class="col-xs-12">
        <?= $form->field($model, 'login', [
                'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon"><span class="glyphicon glyphicon-user form-control-feedback"></span></span></div>',
                'enableLabel'=>false,
                'inputOptions'=>[
                    'autofocus' => 'autofocus',
                    'placeholder'=>'Email/Логин',
                    'tabindex'=>'1',
                ],
        ]); ?>
    </div>
</div>
<div class='row'>
    <div class="col-xs-12">
        <?= $form->field($model, 'password', [
            'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon"><span class="glyphicon glyphicon-lock form-control-feedback"></span></span></div>',
            'enableLabel'=>false,
            'inputOptions'=>[
                'autofocus' => 'autofocus',
                'placeholder'=>'Пароль',
                'tabindex'=>'2',
            ],
        ])->passwordInput(); ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <?=Html::submitButton('Войти в систему',
            [
                'class' => 'btn btn-success btn-block btn-lg',
                'tabindex'=>'3',
            ]
        ); ?>
    </div>
</div>
<br/>
<?php ActiveForm::end();?>
<p>
    <?php  /*if (!$module->registrationDisabled):?>
        <span class="pull-left"><?=CHtml::link('Регистрация', ['/user/account/registration'])?></span>
    <?php endif; ?>
    <?php if (!$module->recoveryDisabled):?>
        <span class="pull-right"><?=CHtml::link('Забыли пароль?', ['/user/account/recovery'])?></span>
    <?php endif; */?>
</p>




