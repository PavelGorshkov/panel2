<?php

/* @var $this  View */
/* @var $searchModel SearchJob */
/* @var $dataProvider ActiveDataProvider */

use app\modules\core\components\View;
use app\modules\core\widgets\CustomActionColumn;
use app\modules\core\widgets\CustomGridView;
use app\modules\cron\models\SearchJob;
use kartik\editable\Editable;
use kartik\grid\GridView;
use kartik\popover\PopoverX;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\cron\models\Job;
use app\modules\cron\helpers\JobStatusListHelper;
use app\modules\cron\helpers\CronHelper;


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
                    'title' => 'Добавить задание',
                    'class' => 'btn btn-success',
                ]
            )
            . ' '.
            Html::a(
                '<i class="glyphicon glyphicon-repeat"></i>',
                ['index'],
                ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => 'Сбросить фильтры'])
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
        'heading' => $this->getSmallTitle(),
    ],
    'persistResize' => false,
    'toggleDataOptions' => ['minCount' => 10],


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
            'header'=>'Описание задания',
            'format' => 'raw',
            'contentOptions' => ['class' => 'kv-align-middle'],
            'headerOptions' => ['class' => 'kv-align-middle'],
            'value' => function($model){
                /** @var $model Job */
                return CronHelper::getCommandActionTitle($model->command);
            },
        ],
        [
            'attribute' => 'module',
            'header'=>'Модуль',
            'format' => 'raw',
            'contentOptions' => ['class' => 'kv-align-middle'],
            'headerOptions' => ['class' => 'kv-align-middle'],
            'filter'=> ArrayHelper::getColumn(app()->moduleManager->getAllModules(),'title',true),
            'value' => function($model){
                /** @var $model Job */
                $module = '';
                if($model->command){
                    $module = explode('/', $model->command);
                }
                $moduleList = ArrayHelper::getColumn(app()->moduleManager->getAllModules(),'title',true);
                return isset($module[0]) && isset($moduleList[$module[0]]) ? $moduleList[$module[0]] : '';
            },
        ],
        [
            'attribute' => 'command',
            'format' => 'raw',
            'contentOptions' => ['class' => 'kv-align-middle'],
            'headerOptions' => ['class' => 'kv-align-middle'],
            'filter' => false,
            'value' => function($model){
                /** @var $model Job */
                $data = '';
                if($model->command && ($elements = explode('/', $model->command))){
                    $data = '<strong>Модуль: </strong>'.$elements[0].'<br>';
                    $data = $data.'<strong>Команда: </strong>'.$elements[1].'<br>';
                    $data = $data.'<strong>Экшен: </strong>'.$elements[2].'<br>';
                }
                return $data;
            }
        ],
        [
            'attribute' => 'is_active',
            'format' => 'raw',
            'contentOptions' => ['class' => 'kv-align-middle'],
            'headerOptions' => ['class' => 'kv-align-middle'],
            'value' => function($model){
                /** @var $model Job */
                return JobStatusListHelper::getList()[$model->is_active];
            },
            'filter'=> JobStatusListHelper::getList(),
            'class' => 'kartik\grid\EditableColumn',
            'hAlign'=>'center',
            'vAlign'=>'middle',
            'editableOptions'=>function() { //$model, $key, $index
                return [
                    'size'=> PopoverX::SIZE_MEDIUM,
                    'inputType'=> Editable::INPUT_DROPDOWN_LIST,
                    'placement'=>PopoverX::ALIGN_TOP,
                    'data'=> JobStatusListHelper::getList(),
                    'submitButton'=>[
                        'icon'=>'<i class="fa fa-fw fa-check"></i>',
                        'label'=>'Применить',
                        'class' => 'btn btn-sm btn-primary',
                    ],
                    'formOptions' => ['action' => Url::to('is_active')]
                ];
            },
        ],
        [
            'attribute' => 'params',
            'format' => 'raw',
            'contentOptions' => ['class' => 'kv-align-middle'],
            'headerOptions' => ['class' => 'kv-align-middle'],
            'filter' => false,
            'value' => function($model){
                /** @var $model Job */
                $data = '';
                if($model->params && ($elements = explode(' ', $model->params))){
                    if(($params = CronHelper::getTimeParamsList()) && (count($params)==count($elements))){
                        $i = 0;
                        foreach($params as $title){
                            $data = $data.'<strong>'.$title.': </strong>'.$elements[$i].'<br>';
                            $i++;
                        }
                    }
                }

                return $data;
            },
        ],
        [
            'class' => CustomActionColumn::className(),
            'template' => '{run} {update} {delete}',
            'buttons' => [
                'run' => function ($url){
                    return Html::a(
                        '<i class="fa fa-play-circle"></i>',
                        $url,
                        ['class'=>'btn btn-success btn-xs', 'title'=>'Запуск команды']
                    );
                },
            ],
        ]

    ]
]);
