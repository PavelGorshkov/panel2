<?php
/* @var $this yii\web\View */



/* @var $form yii\widgets\ActiveForm */
/* @var $generator \app\modules\developer\generators\module\Generator */

?>
<div class="module-form">
<?php
    echo $form->field($generator, 'moduleClass');
    echo $form->field($generator, 'moduleID');
    echo $form->field($generator, 'moduleTitle');
    echo $form->field($generator, 'moduleDirectories');
?>
</div>
