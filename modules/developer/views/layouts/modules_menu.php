<?php

use yii\helpers\Html;
use yii\widgets\Menu;

/* @var $content string */
/* @var $this \app\modules\core\components\View */

$this->beginContent('@app/modules/core/views/layouts/admin.php');
?>
<div class="row">
    <div class="col-md-3">
        <div class="callout callout-default" style="background-color: #fff">
<?php
            $menu=[[
                    'label'=>'Все модули',
                    'url' => [app()->controller->action->id],
                    'active'=>!app()->request->get('module'),
            ]];

            foreach (app()->moduleManager->getListAllModules() as $module) {

                $menu[] = [
                    'label'=>$module,
                    'url' => [app()->controller->action->id, 'module'=>$module],
                    'active'=>app()->request->get('module')==$module,
                ];
            }

            try {
                echo Menu::widget([
                    'items' => $menu,
                    'options' => [
                        'class' => 'nav nav-pills nav-stacked'
                    ],
                    'encodeLabels' => false,
                ]);
            } catch (Exception $e) {

                echo $e->getMessage();
            }
?>
        </div>
        <div style="padding: 5px; text-align: center;">
            <?=Html::a('Обновить все модули', ['migration/refresh-all'], [
                'class'=>'btn btn-info'
            ])?>
        </div>
    </div>
    <div class="col-md-9">
        <?=$content?>
    </div>
</div>
<?php $this->endContent();