<?php

use app\modules\core\components\Module;
use app\modules\core\components\View;
use yii\helpers\Html;

/* @var View $this */
/* @var Module $module */
/* @var array $data */
/* @var string $slug */

echo Html::beginForm();
echo $this->render(
    count($module->getParamGroups())?'_settings_group':'_settings',
    [
        'data'=>$data,
        'module'=>$module,
        'slug'=>$slug,
    ]
);
?>
    <div class="row">
        <div class="col-xs-12">
            <?= Html::submitButton('Сохранить', ['class'=>'btn btn-sm btn-success'])?>
        </div>
    </div>
<?php
echo Html::endForm();