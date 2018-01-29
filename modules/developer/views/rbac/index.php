<?php

use app\modules\core\components\View;
use app\modules\core\widgets\CustomGridView;use app\modules\developer\models\SearchAuthTask;
use yii\data\DataProviderInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this View */
/* @var $searchModel SearchAuthTask */
/* @var $dataProvider DataProviderInterface */

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
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => false,
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'toolbar' => [
                ['content' =>
                    Html::a(
                        '<i class="fa fa-fw fa-plus-square"></i> Создать задачу',
                        ArrayHelper::merge(['create'], $moduleParam),
                        ['class' => 'btn btn-success']
                    )
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
                    'attribute' => 'module',
                    'filter'=>false,
                ],
                [
                    'attribute' => 'className',
                ],
                [
                    'attribute' => 'title',
                ],

            ]
        ]);
?>
    </div>
</div>