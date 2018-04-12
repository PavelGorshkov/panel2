<?php

use app\modules\core\components\Module;
use app\modules\core\widgets\BoxWidget;
use yii\helpers\Html;
use yii\web\View;

/* @var View $this */
/* @var Module $module */
/* @var array $data */
/* @var string $slug */

$setter = $data;
$labels = $module->getParamLabels();

$dropdown = $module->getParamsDropdown();

foreach ($module->getParamGroups() as $params) {

    if (isset($params['visible']) && !$params['visible']) continue;

    BoxWidget::begin([
        'type' => 'info',
        'title' => $params['title'],
    ]);

    foreach ($params['items'] as $item):
        unset($setter[$item])
        ?>

        <div class="form-group col-sm-6">
            <label for="<?= $item ?>"><?= isset($labels[$item]) ? $labels[$item] : $item ?></label>
            <?php if (isset($dropdown[$item])): ?>
                <?= Html::dropDownList(
                    'Settings[' . $item . ']',
                    isset($data[$item])?$data[$item]:null,
                    $dropdown[$item],
                    ['class' => 'form-control', 'id' => $item]
                ) ?>
            <?php else: ?>
                <?= Html::textInput(
                    'Settings[' . $item . ']',
                    isset($data[$item]) ? $data[$item] : '',
                    ['class' => 'form-control', 'id' => $item]
                ) ?>
            <?php endif; ?>
        </div>

    <?php
    endforeach;
    BoxWidget::end();
}
if (count($setter)) {

    echo '<!--';
    printr($setter);
    echo '-->';
}
