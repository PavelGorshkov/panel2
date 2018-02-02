<?php
/** @var $this View */
/** @var $model UserFormModel */

/** @var $module Module */

use app\modules\core\components\View;
use app\modules\core\widgets\BoxWidget;
use app\modules\user\forms\UserFormModel;
use app\modules\user\Module;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->setSmallTitle($model->email);

BoxWidget::begin([
    'type' => BoxWidget::TYPE_SUCCESS,
    'title' => 'Изменение пароля '.$model->email
]);
/* @var $this  View */
/* @var $model UserFormModel */
/* @var $module Module */

$form = ActiveForm::begin([
    'id' => 'manager-user-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
    'validateOnType' => false,
    'validateOnChange' => false,
]);

echo $form->errorSummary($model);
?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'password')->passwordInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'r_password')->passwordInput(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= Html::submitButton('Изменить пароль',
                [
                    'class' => 'btn btn-primary btn-sm',
                    'name' => 'submit-type',
                    'value' => 'index',
                ]
            ); ?>&nbsp;
            <?= Html::a('Отмена', Url::to('index'),
                [
                    'class' => 'btn btn-default btn-sm',
                ]
            ); ?>
        </div>
    </div>
<?php
$form->end();
BoxWidget::end();
