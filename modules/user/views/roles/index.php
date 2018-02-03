<?php

/* @var $this  View */
/* @var $searchModel  SearchRole*/
/* @var $dataProvider DataProviderInterface */

use app\modules\core\helpers\RouterUrlHelper;
use app\modules\core\widgets\CustomActionColumn;
use kartik\grid\GridView;
use app\modules\core\components\View;
use yii\data\DataProviderInterface;
use app\modules\user\models\SearchRole;
use app\modules\core\widgets\CustomGridView;
use yii\helpers\Html;
use yii\helpers\Url;


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
                    'data'=>[
                        'pjax'=>0,
                    ],
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
        [
            'class' => 'kartik\grid\SerialColumn',
            'contentOptions' => ['class' => 'kartik-sheet-style'],
            'width' => '30px',
            'header' => '',
            'headerOptions' => ['class' => 'kartik-sheet-style']
        ],
        [
            'attribute' => 'title',
            'format' => 'raw',
            'value' => function($model) {

                return user()->can(RouterUrlHelper::to(['update']))
                    ?Html::a($model->description, Url::to(['update', 'id'=>$model->id]))
                    :$model->title;
            },
            'contentOptions' => ['class' => 'kv-align-middle'],
            'headerOptions' => ['class\' => \'kv-align-middle'],
        ],
        [
            'attribute' => 'description',
            'format' => 'raw',
            'value' => function($model) {

                return user()->can(RouterUrlHelper::to(['update']))
                    ?Html::a($model->description, Url::to(['update', 'id'=>$model->id]))
                    :$model->description;
            },
            'contentOptions' => ['class' => 'kv-align-middle'],
            'headerOptions' => ['class\' => \'kv-align-middle'],
        ],
        [
            'attribute' => 'created_at',
            'format' => 'date',
            'xlFormat' => "dd\\.mm\\. \\-yyyy",
            'contentOptions' => ['class' => 'kv-align-middle'],
            'headerOptions' => ['class\' => \'kv-align-middle'],
        ],
        [
            'class' => CustomActionColumn::className(),
            'template' => '{access}&nbsp;{update}&nbsp;{password}&nbsp;{delete}',
            'headerOptions' => ['class' => 'col-sm-1'],
            'contentOptions' => ['class' => 'text-right'], // only set when $responsive = false
            'buttons' => [
                'access'=>function ($url, $model) {

                    return Html::a(
                        '<i class="fa fa-lock"></i>',
                        $url,
                        [
                            'class'=>'btn btn-xs btn-success',
                            'data-pjax'=>0,
                            'aria-label'=>'Изменить пароль',
                            'title'=>'Изменить пароль'
                        ]
                    );
                }
            ],
        ]
    ]
]);