<?php

use app\modules\core\components\Module;
use app\modules\core\components\View;
use app\modules\core\widgets\BoxWidget;
use yii\helpers\Html;

/* @var View $this */
/* @var Module $module */
/* @var array $data */
/* @var string $slug */

$setter = $data;
$dropdown = $module->getParamsDropdown();

BoxWidget::begin([
    'type'=>'info',
    'title'=>'Настройки'
]);

foreach ($module->getParamLabels() as $item => $label) :

    unset($setter[$item]);
?>
<div class="form-group col-sm-6">
    <label for="<?=$item?>"><?=$label?$label:$item?></label>
    <?php if (isset($dropdown[$item])):?>
        <?=Html::dropDownList(
            'Settings['.$item.']',
            isset($data[$item])?$data[$item]:null,
            $dropdown[$item],
            ['class'=>'form-control', 'id'=>$item]
        )?>
    <?php else: ?>
        <?=Html::textInput(
            'Settings['.$item.']',
            isset($data[$item])?$data[$item]:'',
            ['class'=>'form-control', 'id'=>$item]
        )?>
    <?php endif; ?>
</div>
<?php
endforeach;;

BoxWidget::end();