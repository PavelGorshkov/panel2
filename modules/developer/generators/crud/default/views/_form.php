<?php

use app\modules\developer\generators\crud\Generator;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

$modelClass = StringHelper::basename($generator->modelClass);

echo "<?php\n";
?>

use app\modules\core\components\View;
use yii\helpers\Html;
use app\modules\core\widgets\ActiveForm;
use <?= ltrim($generator->modelClass, '\\') ?>;
use yii\helpers\Url;
use app\modules\<?= $generator->module?>\Module;

/** @var $this  View */
/** @var $model <?= $modelClass ?> */
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
<?= "?>\n" ?>
<div class="row">

</div>
<div class="row">
    <div class="col-xs-12">
    <?php echo <<<HEREDOC
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
        ); ?> \n
HEREDOC
 ?>
    </div>
</div>
<?= "<?php \n" ?>
$form->end();
