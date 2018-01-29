<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator yii\gii\generators\crud\Generator */

$modules = [];
foreach (app()->moduleManager->getListAllModules() as $module) {

    $modules[$module] = $module;
}


echo $form->field($generator, 'module')->dropDownList($modules);
echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'searchModelClass');
echo $form->field($generator, 'formModelClass');
echo $form->field($generator, 'taskClass');
echo $form->field($generator, 'controllerClass');
echo $form->field($generator, 'viewPath');
echo $form->field($generator, 'baseControllerClass');