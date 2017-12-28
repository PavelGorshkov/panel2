<?php
/* @var $this  View */
/* @var $searchModel SearchUser */
/* @var $dataProvider ActiveDataProvider */

use app\modules\core\components\View;
use app\modules\core\helpers\RouterUrlHelper;
use app\modules\core\widgets\BoxWidget;
use app\modules\core\widgets\CustomGridView;
use app\modules\user\helpers\UserStatusHelper;
use app\modules\user\models\SearchUser;
use app\modules\user\models\User;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;


$this->setBreadcrumbs([
    [
        'label' => $this->getTitle(),
        'url'=>['index'],
        'encode' => false,
    ],
    [
        'label' => $this->getSmallTitle(),
        'encode' => false,
    ]
]);

try {

    BoxWidget::begin([
        'type'=>'success',
        'title'=>$this->getSmallTitle(),
    ]);

    BoxWidget::end();

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
                        'title' => 'Добавить пользователя',
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
                'attribute' => 'username',
                'format' => 'raw',
                'value' => function($model) {

                    return user()->can(RouterUrlHelper::to(['update']))
                        ?Html::a($model->username, Url::to(['update', 'id'=>$model->id]))
                        :$model->username;
                }
            ],
            [
                'header'=>'Информация',
                'attribute'=>'info',
                'format' => 'raw',
                'value' => function($model) {

                    /** @var $model User */
                    return $model->getContact();
                }
            ],
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute'=>'access_level',
                'hAlign'=>'center',
                'vAlign'=>'middle',
                'format'=>'raw',
                'editableOptions'=>function($model, $key, $index) {
                    return [
                        'size'=>\kartik\popover\PopoverX::SIZE_MEDIUM,
                        'inputType'=>\kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                        'data'=>\app\modules\user\helpers\UserAccessLevelHelper::getList(),
                        'submitButton'=>[
                            'icon'=>'<i class="fa fa-fw fa-check"></i>',
                            'label'=>'Применить',
                            'class' => 'btn btn-sm btn-primary',
                        ],
                        ['formOptions' => ['action' => Url::to('editable')]]
                    ];
                },
                'value'=>function ($model) {

                    /** @var $model User */
                    return $model->getAccessGroup();
                },
                'filter'=> User::getAccessLevelList(),
            ],
            [
                'attribute'=>'status',
                'format'=>'raw',
                'value'=>function ($model) {

                    /** @var $model User */
                    return UserStatusHelper::getValue($model->status, true);
                },
                'filter'=> UserStatusHelper::getList(),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{access}{update}{password}{delete}',
                'buttons' => [
                    ''
                ],
            ]

        ]
    ]);

} catch (Exception $e) {

    echo $e->getMessage();
}