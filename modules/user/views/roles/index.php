<?php

/* @var $this  View */
/* @var $searchModel  SearchRole*/
/* @var $dataProvider DataProviderInterface */

use kartik\grid\GridView;
use app\modules\core\components\View;
use yii\data\DataProviderInterface;
use app\modules\user\models\SearchRole;
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
                    'title' => 'Добавить роль',
                    'class' => 'btn btn-success',
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
        'heading' => 'Пользовательские роли',
    ],
    'persistResize' => false,
    'columns' => [

    ]
]);