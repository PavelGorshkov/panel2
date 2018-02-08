<?php

/** @var $this View */
/** @var $model ManagerUser */
/** @var $role Role */
/** @var $operations array */
/** @var $data array */
/** @var $dataRoles array */

use app\modules\core\components\View;
use app\modules\core\widgets\BoxBodyWidget;
use app\modules\user\models\ManagerUser;
use app\modules\user\models\Role;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->setTitle($model->username);
$this->setSmallTitle('Предоставление доступа');

$form = ActiveForm::begin([
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
]);

?>
    <div class="row">
        <div class="col-sm-3">
            <? BoxBodyWidget::begin()?>
            <ul class="nav nav-pills nav-stacked" role="tablist" id="tabs">
                <?php
                $active = true;
                foreach ($operations as $module => $temp):

                    $uid = $module.'_tasks';
                    ?>
                    <li role="presentation" <?= $active ? 'class="active"' : '' ?>>
                        <?= Html::a(
                            app()->moduleManager->getTitle($module),
                            '#' . $uid,
                            [
                                'aria-controls' => $uid,
                                'role' => "tab",
                                'data-toggle' => "tab",
                            ]
                        ) ?>
                        <?php if ($active) $active = false; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <? BoxBodyWidget::end()?>
        </div>
        <div class="col-sm-9 tab-content" id="tab_module">
            <?php
            BoxBodyWidget::begin();

            $active = true;
            foreach ($operations as $module => $tasks):

                echo $this->render('_module_access', [
                    'model' => $model,
                    'module' => $module,
                    'tasks' => $tasks,
                    'active' => $active,
                    'data'=>$data,
                    'role'=>$role,
                    'dataRoles'=>$dataRoles,
                ]);
                if ($active) $active = false;
            endforeach;

            BoxBodyWidget::end();
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= Html::submitButton('Сохранить и продолжить',
                [
                    'class' => 'btn btn-primary btn-sm',
                ]
            ); ?>&nbsp;
            <?= Html::submitButton('Сохранить и закрыть',
                [
                    'class' => 'btn btn-info btn-sm',
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

$this->registerCss(/** @lang text */
    <<<CSS

    .tab-pane {
    
        display: none;
    }

    .tab-pane.active {
    
        display: block;
    }
CSS
);

$this->registerJs(/** @lang text */
    <<<JS

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

