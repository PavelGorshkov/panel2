<?php

use app\modules\core\components\View;
use app\modules\user\forms\RoleFormModel;
use yii\helpers\Html;
use app\modules\core\widgets\ActiveForm;
use yii\helpers\Url;
use app\modules\user\Module;

/** @var $this  View */
/** @var $model RoleFormModel */
/** @var $module Module */


try {
    $form = ActiveForm::begin([
        'id' => $model->formName() . '-form',
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
        'validateOnBlur' => false,
        'validateOnType' => false,
        'validateOnChange' => false,
        'options' => [
            /*'enctype' => 'multipart/form-data',*/
        ]
    ]);
} catch (\yii\base\InvalidConfigException $e) {

    echo $e->getMessage();
}

echo $form->errorSummary($model);
?>
    <div class="row">
        <div class="col-sm-8">
            <?= $form->field($model, 'title') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            <?= $form->field($model, 'description')->textarea(['rows' => 7]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= Html::submitButton('Сохранить',
                [
                    'class' => 'btn btn-primary btn-sm',
                    'name' => 'submit-type',
                    'value' => 'index',
                ]
            ); ?>&nbsp;
            <?= Html::submitButton('Применить',
                [
                    'class' => 'btn btn-info btn-sm',
                    'name' => 'submit-type',
                ]
            ); ?>
            <?= Html::a('Отмена', Url::to('index'),
                [
                    'class' => 'btn btn-default btn-sm',
                ]
            ); ?>
        </div>
    </div>
<?php
$form->end();
