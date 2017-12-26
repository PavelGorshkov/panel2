<?php

/* @var $this View */
/* @var $model EmailProfileForm */
/* @var $module Module */

use app\modules\core\components\View;
use app\modules\user\forms\EmailProfileForm;
use app\modules\user\Module;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


$this->setBreadcrumbs([
    [
        'label' => $this->getTitle(),
        'url'=>['view'],
        'encode' => false,
    ],
    [
        'label' => $this->getSmallTitle(),
        'encode' => false,
    ]
]);

$form = ActiveForm::begin([
    'id'=>'profile-email-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
    'validateOnType' => false,
    'validateOnChange' => false,
    'options'=> [
        'class'=>'well',
    ]
]);
echo $form->errorSummary($model);


?>
<div class="row">
    <div class="col-xs-6">
        <?= $form->field($model, 'email', [
                'inputOptions'=>[
                    'placeholder'=>$model->getAttributeLabel('email'),
                    'class'=>'form-control',
                ],
            ])->input('email'); ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <?=Html::submitButton('Изменить Email',
            [
                'class' => 'btn btn-success btn-sm',
            ]
        ); ?>
        <?= Html::a('Oтмена', Url::to(['view']), ['class' => 'btn btn-sm btn-default']); ?>
    </div>
</div>
<br/>
<?php
$form->end();
