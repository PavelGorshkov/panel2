<?php

use app\modules\core\components\View;
use app\modules\core\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\cron\helpers\JobStatusListHelper;
use app\modules\cron\widgets\CronTimeElement;
use app\modules\cron\forms\JobScheduleFormModel;


/* @var $this  View */
/* @var $model  JobScheduleFormModel */


$form = ActiveForm::begin([
    'id'=>'manager-job-form',
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
            <?=$form->field($model, 'is_active')
                ->dropDownList(JobStatusListHelper::getList());?>
        </div>
        <div class="col-sm-6">
            <?=$form->field($model, 'command')
                    ->dropDownList(\app\modules\cron\helpers\CronHelper::getCommandActionList());?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">

            <div class="col-lg-4 col-md-6 col-sm-6">
                <?php echo CronTimeElement::widget(['model' => $model, 'attribute' => 'minute', 'columnCount' => 3]); ?>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6">
                <?php echo CronTimeElement::widget(['model' => $model, 'attribute' => 'hour', 'columnCount' => 4]); ?>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6">
                <?php echo CronTimeElement::widget(['model' => $model, 'attribute' => 'day', 'columnCount' => 4]); ?>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6">
                <?php echo CronTimeElement::widget(['model' => $model, 'attribute' => 'month', 'columnCount' => 2]); ?>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6">
                <?php echo CronTimeElement::widget(['model' => $model, 'attribute' => 'weekDay', 'columnCount' => 2]); ?>
            </div>

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
