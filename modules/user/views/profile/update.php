<?php
/* @var $this app\modules\core\components\View */
/* @var $model ProfileForm */

/* @var $module Module */

use app\modules\user\forms\ProfileForm;
use app\modules\user\helpers\EmailConfirmStatusHelper;
use app\modules\user\helpers\RegisterFromHelper;
use app\modules\user\Module;
use app\modules\user\widgets\AvatarWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\MaskedInput;

$this->setBreadcrumbs([
    [
        'label' => $this->getTitle(),
        'url' => ['view'],
        'encode' => false,
    ],
    [
        'label' => $this->getSmallTitle(),
        'encode' => false,
    ]
]);

$form = ActiveForm::begin([
    'id' => 'profile-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
    'validateOnType' => false,
    'validateOnChange' => false,
    'options' => [
        'class' => 'well',
        'enctype' => 'multipart/form-data',
    ]
]);
echo $form->errorSummary($model);
?>
<div class="row">
    <div class="col-sm-4">
        <?php try {
            echo AvatarWidget::widget();
        } catch (Exception $e) {
            echo $e->getMessage();
        } ?>
    </div>
    <div class="col-sm-8">
        <?= $form->field($model, 'avatar_file')->fileInput() ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-8 col-lg-offset-4">
        <?= $form->field($model, 'full_name') ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <?= $form->field($model, 'email', [
            'inputTemplate' =>
                '<div class="input-group">{input}
                    <span class="input-group-addon">
                        ' . Html::a('Сменить', Url::to(['email'])) . '
                    </span>
                </div>',
            'inputOptions' => [
                'disabled' => true,
                'placeholder' => 'Email',
                'class' => EmailConfirmStatusHelper::isConfirmedEmail(user()->identity) ? 'text-success form-control' : 'form-control',
            ],
        ]) ?>
        <?php if (EmailConfirmStatusHelper::isConfirmedEmail(user()->identity)): { ?>
            <p class="email-status-confirmed text-success">E-Mail проверен</p>
        <?php } else: { ?>
            <p class="email-status-not-confirmed text-error">E-mail не подтвержден, проверьте почту!</p>
        <?php } endif ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'phone')->widget(
            MaskedInput::class,
            [
                'mask' => $module->phoneMask,
                'options' => [
                    'placeholder' => $model->getAttributeLabel('phone'),
                    'class' => 'form-control',
                ]
            ]) ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <?= $form->field($model, 'department')->textarea(['rows' => 7, 'placeholder' => $model->getAttributeLabel('department')]); ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <?= Html::submitButton('Сохранить профиль',
            [
                'class' => 'btn btn-success btn-sm',
                'tabindex' => '9',
            ]
        ); ?>
        <?= Html::a('Oтмена', Url::to(['view']), ['class' => 'btn btn-sm btn-default']); ?>
        <?php if (!RegisterFromHelper::isLdap(app()->user->identity)) : ?>
            <?= Html::a('<i class="fa fa-lock"></i> Сменить пароль', ['change-password'], ['class' => 'btn btn-sm btn-info']); ?>
        <?php endif; ?>
    </div>
</div>
<br/>
<?php ActiveForm::end(); ?>

