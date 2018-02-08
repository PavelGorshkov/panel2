<?php

use app\modules\user\models\Role;
use dosamigos\switchinput\SwitchBox;
use yii\helpers\Html;
use yii\web\View;

/** @var $this View */
/** @var $model Role */
/** @var $tasks array */
/** @var $module array */
/** @var $active bool */
/** @var $data array */
?>
<div role="tabpanel" class="tab-pane <?= $active ? 'active' : '' ?>" id="<?= $module ?>_tasks">
    <div class="row">
        <div class="col-sm-4">
            <ul class="nav nav-pills nav-stacked" role="tablist" id="nav_<?= $module ?>">
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
        <div class="col-sm-8 tab-content" id="tab_<?= $module ?>">
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
                                <div class="col-sm-3">
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
                                <div class="col-sm-9">
                                    <label for="<?= $operator ?>" style="cursor: pointer"><?= $item ?></label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
