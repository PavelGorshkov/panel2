<?php

/* @var $this  View */
/* @var $searchModel  PeriodSearch*/
/* @var $dataProvider DataProviderInterface */

use kartik\grid\GridView;
use app\modules\core\components\View;
use yii\data\DataProviderInterface;
use app\modules\rating\models\search\PeriodSearch;
use app\modules\core\widgets\CustomGridView;
use yii\helpers\Html;


echo CustomGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'pjax' => true,
    'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
    'toolbar' =>  [
      ['content' =>
             Html::a(
                 '<i class="glyphicon glyphicon-plus"></i>',
                ['create'],
                [
                    'title' => 'Добавить',
                    'class' => 'btn btn-success',
                    'data' => [
                        'pjax'=>0,
                    ]
                ]
             )
        ], 
        '{export}',
        '{toggleData}',
    ],
    'export' => [
        'fontAwesome' => true
    ],
    'bordered' => true,
    'striped' => false,
    'condensed' => true,
    'responsive' => false,
    'hover' => true,
    //'showPageSummary' => true,
    'panel' => [
        'type' => GridView::TYPE_SUCCESS,
       // 'heading' => 'Title',
    ],
    'persistResize' => false,
    'columns' => [
        'id', 'title', 'status'
    ]
]);