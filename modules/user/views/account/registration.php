<?php

/* @var $this app\modules\core\components\View */
/* @var $model app\modules\user\forms\RegistrationForm */
/* @var $profile app\modules\user\forms\ProfileRegistrationForm */

/* @var $module app\modules\user\Module */

use app\modules\core\components\View;
use yii\base\InvalidConfigException;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Button;
use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

$this->setTitle('Регистрация');

?>
    <p class="login-box-msg">Регистрация</p>
<?php
$form = ActiveForm::begin([
    'id' => 'registration-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
    'validateOnType' => false,
    'validateOnChange' => false,
    'options' => [
        'class' => 'form-signin form'
    ]
]);
$form->errorSummary($model);

$formName = $model->formName();
$this->registerJs(/** @lang text */
    <<<JS
!function ($) {
$(function() {

    function str_rand(minlength) {
        
        var result = '',
            words = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM',
            max_position = words.length - 1;
        
        for (var i = 0; i < minlength; ++i) {
            
            var position = Math.floor(Math.random() * max_position);
            result = result + words.substring(position, position + 1);
        }
        return result;
    }

    $('#generate_password').click(function (e) {

        e.preventDefault();

        var pass = str_rand($(this).data('minlength')),
            formName = $(this).data('form-id');

        $('#'+formName+'-password').attr('type', 'text');
        $('#'+formName+'-password').val(pass);
        $('#'+formName+'-r_password').val(pass);
    });
})
}(window.jQuery)
JS
    , View::POS_END);
?>
<?php if (!$module->generateUserName) : ?>
    <div class='row'>
        <div class="col-xs-12">
            <?= $form->field($model, 'username', [
                'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon"><span class="glyphicon glyphicon-user form-control-feedback"></span></span></div>',
                'enableLabel' => false,
                'inputOptions' => [
                    'autofocus' => 'autofocus',
                    'placeholder' => $model->getAttributeLabel('username'),
                    'tabindex' => '1',
                ],
            ]); ?>
        </div>
    </div>
<?php endif; ?>
    <div class='row'>
        <div class="col-sm-12">
            <?= $form->field($model, 'email', [
                'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon"><span class="glyphicon glyphicon-envelope form-control-feedback"></span></span></div>',
                'enableLabel' => false,
                'inputOptions' => [
                    'autofocus' => 'autofocus',
                    'placeholder' => $model->getAttributeLabel('email'),
                    'tabindex' => '2',
                ],
            ])->input('email'); ?>
        </div>
    </div>
    <div class='row'>
        <div class="col-sm-12">
            <?= $form->field($model, 'password', [
                'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon"><span class="glyphicon glyphicon-lock form-control-feedback"></span></span></div>',
                'enableLabel' => false,
                'inputOptions' => [
                    'autofocus' => 'autofocus',
                    'placeholder' => $model->getAttributeLabel('password'),
                    'tabindex' => '3',
                ],
            ])->passwordInput(); ?>
        </div>
    </div>
    <div class='row'>
        <div class="col-sm-12">
            <?= $form->field($model, 'r_password', [
                'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon"><span class="glyphicon glyphicon-lock form-control-feedback"></span></span></div>',
                'enableLabel' => false,
                'inputOptions' => [
                    'autofocus' => 'autofocus',
                    'placeholder' => $model->getAttributeLabel('r_password'),
                    'tabindex' => '4',
                ],
            ])->passwordInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 form-group">
            <?php try {
                echo Button::widget(
                    [
                        'label' => 'Сгенерировать пароль',
                        'options' => [
                            'id' => 'generate_password',
                            'data-minlength' => $module->minPasswordLength,
                            'class' => 'btn btn-block btn-sm',
                            'data-form-id' => $model->formName(),
                        ],
                    ]
                );
            } catch (Exception $e) {

                echo $e->getMessage();
            } ?>
        </div>
    </div>
    <div class='row'>
        <div class="col-sm-12">
            <?= $form->field($profile, 'full_name', [
                'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon"><span class="glyphicon glyphicon-user form-control-feedback"></span></span></div>',
                'enableLabel' => false,
                'inputOptions' => [
                    'autofocus' => 'autofocus',
                    'placeholder' => $profile->getAttributeLabel('full_name'),
                    'tabindex' => '5',
                ],
            ]); ?>
        </div>
    </div>
    <div class='row'>
        <div class="col-sm-12">
            <?= $form->field($profile, 'department', [
                'enableLabel' => false,
                'inputOptions' => [
                    'autofocus' => 'autofocus',
                    'placeholder' => $profile->getAttributeLabel('department'),
                    'tabindex' => '6',
                ],
            ])->textarea(['rows' => 2]); ?>
        </div>
    </div>
    <div class='row'>
        <div class="col-sm-12">
            <?= $form->field($profile, 'phone', [
                'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon"><span class="glyphicon glyphicon-phone form-control-feedback"></span></span></div>',
                'enableLabel' => false,
                'inputOptions' => [
                    'autofocus' => 'autofocus',
                    'placeholder' => $profile->getAttributeLabel('phone'),
                    'tabindex' => '7',
                ],
            ])->widget(
                MaskedInput::class,
                [
                    'mask' => $module->phoneMask,
                    'options' => [
                        'placeholder' => $profile->getAttributeLabel('phone'),
                        'class' => 'form-control',
                        'tabindex' => '8',
                    ]
                ]) ?>
        </div>
    </div>
<?php
try {
    if ($module->showCaptcha && Captcha::checkRequirements()): { ?>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'verifyCode',
                    [
                        'enableLabel' => false,
                        'inputOptions' => [
                            'autofocus' => 'autofocus',
                            'placeholder' => $model->getAttributeLabel('verifyCode'),
                            'tabindex' => '9',
                        ],
                    ]
                )->widget(Captcha::class, [
                    'captchaAction' => '/site/captcha',
                    'template' => '{image} <div class="input-group">
                                {input}<span class="input-group-addon"><span class="glyphicon glyphicon-picture form-control-feedback"></span></span>
                            </div>
                            <span class="help-block">Введите текст указанный на картинке</span>'
                ]); ?>
            </div>
        </div>
    <?php } endif;
} catch (InvalidConfigException $e) {
    $e->getMessage();
}
?>
    <div class="row">
        <div class="col-xs-12">
            <?= Html::submitButton('Зарегистрироваться',
                [
                    'class' => 'btn btn-primary btn-block btn-lg',
                    'tabindex' => '10',
                ]
            ); ?>
        </div>
    </div>
    <br/>
<?php ActiveForm::end(); ?>
<?= Html::a('Вернуться', ['/login']) ?>