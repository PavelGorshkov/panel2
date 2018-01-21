<?php
/* @var $this app\modules\core\components\View */

use app\modules\core\widgets\CustomGridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $model app\modules\developer\models\MigrationList */

$this->setSmallTitle('Список');

$this->setBreadcrumbs([
    [
        'label' => $this->getTitle(),
        'url' => ['index'],
        'encode' => false,
    ],
    [
        'label' => $this->getSmallTitle(),
        'encode' => false,
    ]
]);

$moduleParam = app()->request->get('module') ? ['module' => app()->request->get('module')] : [];
?>
<div class="row">
    <div class="col-sm-12">
        <?php
        echo CustomGridView::widget([
            'dataProvider' => $model->search(),
            'filterModel' => null,
            'pjax' => false,
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'toolbar' => [
                ['content' =>
                    Html::a(
                        '<i class="fa fa-fw fa-plus-square"></i> Создание миграции',
                        ArrayHelper::merge(['create'], $moduleParam),
                        ['class' => 'btn btn-success']
                    )
                ],
                ['content'=>
                    (count($moduleParam)
                        ?Html::a(
                            '<i class="fa fa-fw fa-refresh"></i> Обновить БД модуля',
                            ArrayHelper::merge(['refresh'], $moduleParam),
                            ['class' => 'btn btn-info']
                        )
                        :'')
                ],
                '{export}',
                '{toggleData}',
            ],
            'export' => [
                'fontAwesome' => true
            ],
            'bordered' => false,
            'striped' => false,
            'condensed' => false,
            'responsive' => false,
            'hover' => true,
            //'showPageSummary' => true,
            'panel' => [
                'type' => CustomGridView::TYPE_SUCCESS,
                'heading' => $this->getSmallTitle(),
            ],
            'persistResize' => false,
            'toggleDataOptions' => ['minCount' => 10],
            'columns' => [
                [
                    'label' => "Модуль",
                    'attribute' => 'module',
                    'value' => function ($data) {
                        return $data["module"];
                    }
                ],
                [
                    'label' => "Класс",
                    'attribute' => 'classname',
                    'value' => function ($data) {
                        return $data["classname"];
                    }
                ],
                [
                    'label' => 'Дата создания',
                    'attribute' => 'createtime',
                    'value' => function ($data) {
                        /* @var $date \DateTime */
                        $date = $data['createtime'];

                        return $date->format("Y-m-d H:i:s");
                    },
                ],

            ]
        ]);
?>
    </div>
</div>





