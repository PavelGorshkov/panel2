<?php

/** @var $this View */
/** @var $model Role */
/** @var $operations array */

/** @var $data array */

use app\modules\core\components\View;
use app\modules\core\widgets\BoxWidget;
use app\modules\user\models\Role;
use dosamigos\switchinput\SwitchBox;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->setTitle($model->title);
$this->setSmallTitle('Предоставление доступа');

$form = ActiveForm::begin([
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
]);

foreach ($operations as $module => $tasks):

    BoxWidget::begin([
        'type' => BoxWidget::TYPE_SUCCESS,
        'title' => app()->moduleManager->getTitle($module),
    ]);
    ?>
    <div class="row">
        <div class="col-sm-4">
            <ul class="nav nav-pills nav-stacked" role="tablist">
                <?php
                $active = true;
                foreach ($tasks as $id => $task):

                    $uid = str_replace('/', '_', $id);
                    ?>
                    <li role="presentation" <?= $active ? 'class="active"' : '' ?>>
                        <?= Html::a(
                            $task['label'],
                            '#' . $uid,
                            [
                                'aria-controls' => $uid,
                                'role' => "tab",
                                'data-toggle' => "tab",
                            ]
                        ) ?>
                        <?php $active = false; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col-sm-8 tab-content">
            <?php
            $active = true;
            foreach ($tasks as $id => $task):

                $uid = str_replace('/', '_', $id);
                ?>
                <div role="tabpanel" class="tab-pane <?= $active ? 'active' : '' ?>" id="<?= $uid ?>">
                    <?php $active = false ?>
                    <div class="form-group">
                        <?= Html::a(
                            'Разрешить все операции',
                            'javascript:void(0)',
                            [
                                'data-task' => $uid . '_trigger',
                                'data-switch-value' => 'true',
                                'class' => 'switch'
                            ]); ?> /
                        <?= Html::a(
                            'Запретить все операции',
                            'javascript:void(0)',
                            [
                                'data-task' => $uid . '_trigger',
                                'data-switch-value' => 'false',
                                'class' => 'switch'
                            ]) ?>
                    </div>
                    <div id="<?= $uid ?>_trigger">
                        <?php foreach ($task['item'] as $action => $item): ?>
                            <div class="row form-group">
                                <div class="col-sm-2">
                                    <?php
                                    $operator = str_replace('/', '_', $action);
                                    echo SwitchBox::widget([
                                        'name' => 'Access[' . $action . ']',
                                        'checked' => isset($data[$action]),
                                        'clientOptions' => [
                                            'size' => 'small',
                                            'onColor' => 'success',
                                            'offColor' => 'danger',
                                            'onText' => 'Вкл',
                                            'offText' => 'Выкл',
                                        ],
                                        'options' => [
                                            'id' => $operator,
                                        ]
                                    ]);
                                    ?>
                                </div>
                                <div class="col-sm-10s">
                                    <label for="<?= $operator ?>" style="cursor: pointer"><?= $item ?></label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    BoxWidget::end();

endforeach;
?>
    <div class="row">
        <div class="col-sm-12">
            <?= Html::submitButton('Сохранить и продолжить',
                [
                    'class'=>'btn btn-primary btn-sm',
                    'name' => 'submit-type',
                ]
            ); ?>&nbsp;
            <?= Html::submitButton('Сохранить и закрыть',
                [
                    'class'=>'btn btn-info btn-sm',
                    'name' => 'submit-type',
                    'value' => 'index'
                ]); ?>&nbsp;
            <?= Html::a('Отмена', Url::to('index'),
                [
                    'class' => 'btn btn-default btn-sm',
                ]); ?>
        </div>
    </div>
<?php
$form::end();

$this->registerJs(<<<JS

!function ($) {
$(function() {
        
    $('.switch').on('click', function() {

        var id_div = $(this).data('task'),
            switch_value = $(this).data('switch-value');

        $('#'+id_div+' input').each(function() {

            $(this).bootstrapSwitch('state', switch_value);
        })
    })
})
}(window.jQuery)
JS
    , View::POS_END);

