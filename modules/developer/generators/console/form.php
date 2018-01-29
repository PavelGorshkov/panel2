<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator \app\modules\developer\generators\console\Generator */

$modules = [];
foreach ($generator->getListModule() as $module) {

    $modules[$module] = $module;
}

echo $form->field($generator, 'module')->dropDownList($modules);
echo $form->field($generator, 'controllerClass');
echo $form->field($generator, 'actions');
echo $form->field($generator, 'baseClass');
echo $form->field($generator, 'hasCron')->checkbox();
