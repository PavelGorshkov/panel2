<?php
/* @var $this app\modules\core\components\View */
/* @var $modules [] */
/* @var $form yii\bootstrap\ActiveForm */

use yii\helpers\Html;

$allModules = app()->moduleManager->getAllModules();
$enabledModules = app()->moduleManager->getEnabledModules();

echo Html::beginForm('', 'post', ['id'=>'modules-form']);
?>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Приоритет</th>
            <th>Название</th>
            <th>Зависимые модули</th>
            <th colspan="2"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($modules as $module => $moduleData):?>
        <tr>
            <td class="col-sm-1 text-center">
                <?=Html::textInput(
                    !$moduleData['is_system']?"States[".$module."]":null,
                    $moduleData['priority'],
                    [
                        'class'=>"form-control text-center",
                        'readonly'=>'readonly',
                    ]
                );?>
            </td>
            <td>
                <?=$moduleData['is_system']
                    ? '<span class="text-danger"><i class="fa fa-fw fa-flash"></i></span>'
                    : '';
                ?>
                <?=$moduleData['title']?> <span class="label label-info"><?=$module?></span>
            </td>
            <td><?php
                    foreach ($moduleData['dependent'] as $m) {

                        if (isset($enabledModules[$m])) $label = 'label-success';
                        else $label = 'label-danger';

                        echo sprintf('<span class="label %s">%s</span><br />', $label, $allModules[$m]['title']);
                    }
                ?>
            </td>
            <td>
                <?php if ($moduleData['paramsCounter'] /*&& user()->checkAccess(TaskModule::OPERATION_SETTINGS)*/):?>
                    <?=Html::a('<i class="fa fa-fw fa-cog"></i>', ['settings', 'module'=>$module], ['class'=>'btn btn-default btn-xs', 'title'=>'Настройки'])?>
                <?php endif;?>
            </td>
            <td>
                <?php if (!$moduleData['is_system']  /* && user()->checkAccess(TaskModule::OPERATION_SETTINGS) */):?>
                    <?=Html::a('<i class="fa fa-fw fa-power-off"></i>', ['off', 'module'=>$module], ['class'=>'btn btn-danger btn-xs', 'title'=>'Отключить модуль'])?>
                <?php endif;?>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
<div class="row">
    <div class="col-xs-12">
        <?=Html::submitButton('Сохранить', ['class'=>'btn btn-sm btn-success']);?>
    </div>
</div>

<?php
echo Html::endForm();


