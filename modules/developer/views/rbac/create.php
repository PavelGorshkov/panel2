<?php
/* @var $this View */
/* @var $model AuthTaskFormModel */

use app\modules\core\components\View;
use app\modules\core\widgets\BoxWidget;
use app\modules\developer\forms\AuthTaskFormModel;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->setBreadcrumbs([
    [
        'label' => $this->getTitle(),
        'url'=>['index'],
        'encode' => false,
    ],
    [
        'label' => $this->getSmallTitle(),
        'encode' => false,
    ]
]);

$this->setSmallTitle('Новая задача');

$modules = [];
foreach (app()->moduleManager->getListAllModules() as $module) {

    $modules[$module] = $module;
}

BoxWidget::begin([
    'type'=>BoxWidget::TYPE_SUCCESS,
    'title'=>$this->getSmallTitle(),
]);
$form = ActiveForm::begin([
    'id'=>'create-task-form',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
    'validateOnType' => false,
    'validateOnChange' => false,
    'options'=> [
        'class'=>'form'
    ]
]);
$form->errorSummary($model);
?>
<div class="row">
    <div class="col-sm-6">
        <?= $form->field($model, 'module',[
            'options'=>[
                'data-original-title' => $model->getAttributeLabel('module'),
                'data-content'        => $model->getAttributeDescription('module'),
                'class'               => 'popover-help',
                'data-placement'      => 'top',
            ],
        ])->dropDownList(ArrayHelper::merge([''=>'Выберите модуль'], $modules));
        ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'className',
            [
                'options' => [
                    'data-original-title' => $model->getAttributeLabel('className'),
                    'data-content'        => $model->getAttributeDescription('className'),
                    'class'               => 'popover-help',
                    'data-placement'      => 'bottom',
                ],
            ]
        ); ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <?= $form->field($model, 'url',
            [
                'options' => [
                    'data-original-title' => $model->getAttributeLabel('url'),
                    'data-content'        => $model->getAttributeDescription('url'),
                    'class'               => 'popover-help',
                    'data-placement'      => 'bottom',
                ],
            ]
        ); ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'title',
            [
                'options' => [
                    'data-original-title' => $model->getAttributeLabel('title'),
                    'data-content'        => $model->getAttributeDescription('title'),
                    'class'               => 'popover-help',
                    'data-placement'      => 'bottom',
                ],
            ]
        ); ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <?=Html::submitButton('Создать',
            [
                'class' => 'btn btn-info btn-sm',
            ]
        ); ?>
        <?php
            $url = ['index'];
            if ($model->module) $url = ArrayHelper::merge($url, ['module'=>$model->module]);
        ?>
        <?=Html::a('Вернуться', Url::to($url), ['class'=>'btn btn-default btn-sm'])?>
    </div>
</div>
<?php
    $form->end();
BoxWidget::end();
?>

