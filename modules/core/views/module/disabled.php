<?php

/* @var $this View */
/* @var $modules [] */

use app\modules\core\auth\ModuleTask;
use app\modules\core\components\View;
use yii\helpers\Html;

$allModules = app()->moduleManager->getAllModules();
$enabledModules = app()->moduleManager->getEnabledModules();

?>
<table class="table table-hover">
    <thead>
    <tr>
        <th>Название</th>
        <th>Модуль, от которых зависит</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($modules as $module => $moduleData):?>
        <tr>
            <td>
                <?=$moduleData['title']?> <span class="label label-info"><?=$module?></span>
            </td>
            <td><?php
                foreach ($moduleData['dependsOn'] as $m) {

                    if (isset($enabledModules[$m])) $label = 'label-success';
                    else $label = 'label-danger';

                    echo sprintf('<span class="label %s">%s</span><br />', $label, $allModules[$m]['title']);
                }
                ?>
            </td>
            <td>
                <?php if (!$moduleData['is_system']  && user()->can(ModuleTask::OPERATION_DISABLED) ):?>
                    <?=Html::a(
                            '<i class="fa fa-fw fa-power-off"></i>',
                            ['on', 'module'=>$module],
                            [
                                'class'=>'btn btn-success btn-xs',
                                'title'=>'Включить модуль',
                                'data'=>[
                                    'method'=>'post',
                                ]
                            ])?>
                <?php endif;?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
