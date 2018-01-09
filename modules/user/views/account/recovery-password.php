<?php
/* @var $this app\modules\core\components\View */
/* @var $model app\modules\user\forms\RecoveryPasswordForm */
/* @var $module app\modules\user\Module */

use app\modules\core\components\View;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Button;
use yii\helpers\Html;

$this->setTitle('Восстановление пароля');

?>
    <p class="login-box-msg">Восстановление пароля</p>
<?php
$form = ActiveForm::begin([
    'id'=>'recovery_password-form',
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

$formName = $model->formName();
$this->registerJs(/** @lang text */
    <<<JS
!function ($) {
$(function() {

    function str_rand(minlength) {
        var result = '';
        var words = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
        var max_position = words.length - 1;
        for (i = 0; i < minlength; ++i) {
            position = Math.floor(Math.random() * max_position);
            result = result + words.substring(position, position + 1);
        }
        return result;
    }

    $('#generate_password').click(function (e) {

        e.preventDefault();

        var pass = str_rand($(this).data('minlength'));

        $('#'+'$formName'+'-password').attr('type', 'text');
        $('#'+'$formName'+'-password').val(pass);
        $('#'+'$formName'+'-r_password').val(pass);
    });
})
}(window.jQuery)
JS
    , View::POS_END );
?>
    <div class='row'>
        <div class="col-sm-12">
            <?= $form->field($model, 'password', [
                'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon"><span class="glyphicon glyphicon-lock form-control-feedback"></span></span></div>',
                'enableLabel'=>false,
                'inputOptions'=>[
                    'autofocus' => 'autofocus',
                    'placeholder'=>$model->getAttributeLabel('password'),
                    'tabindex'=>'6',
                ],
            ])->passwordInput(); ?>
        </div>
    </div>
    <div class='row'>
        <div class="col-sm-12">
            <?= $form->field($model, 'r_password', [
                'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon"><span class="glyphicon glyphicon-lock form-control-feedback"></span></span></div>',
                'enableLabel'=>false,
                'inputOptions'=>[
                    'autofocus' => 'autofocus',
                    'placeholder'=>$model->getAttributeLabel('r_password'),
                    'tabindex'=>'7',
                ],
            ])->passwordInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 form-group">
            <?=Button::widget(
                [
                    'label' => 'Сгенерировать пароль',
                    'options' => [
                        'id' => 'generate_password',
                        'data-minlength' => $module->minPasswordLength,
                        'class'=>'btn btn-block btn-sm'
                    ],
                ]
            ); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?=Html::submitButton('Изменить пароль',
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