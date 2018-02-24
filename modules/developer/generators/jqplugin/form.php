<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator \app\modules\developer\generators\jqplugin\Generator */

?>
<div class="module-form">
<?php
    echo $form->field($generator, 'pluginName');
    echo $form->field($generator, 'pluginPath');
    echo $form->field($generator, 'pluginFileName');
?>
</div>
