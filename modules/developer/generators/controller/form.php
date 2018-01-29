<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator \app\modules\developer\generators\controller\Generator */

$modules = [];
foreach (app()->moduleManager->getListAllModules() as $module) {

    $modules[$module] = $module;
}

echo $form->field($generator, 'module')->dropDownList($modules);
echo $form->field($generator, 'controllerClass');
echo $form->field($generator, 'titleClass');
echo $form->field($generator, 'actions');
echo $form->field($generator, 'viewPath');
echo $form->field($generator, 'baseClass')->dropDownList($generator->getBaseClassList());
echo $form->field($generator, 'taskClass');
echo $form->field($generator, 'existsActionsMethod')->checkbox();
