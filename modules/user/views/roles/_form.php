<?php

use app\modules\core\components\View;
use yii\helpers\Html;
use app\modules\core\widgets\ActiveForm;
use app\modules\user\models\Role;
use yii\helpers\Url;
use app\modules\user\Module;

/** @var $this  View */
/** @var $model Role */
/** @var $module Module */


$form = ActiveForm::begin([
    'id'=>$model->formName().'-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
    'validateOnType' => false,
    'validateOnChange' => false,
    'options'=> [
         /*'enctype' => 'multipart/form-data',*/
    ]
]);

echo $form->errorSummary($model);
?>
<div class="row">

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
