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
use yii\widgets\Pjax;


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
                Html::button('<i class="glyphicon glyphicon-plus"></i>', ['type' => 'button', 'title' => Yii::t('kvgrid', 'Add Book'), 'class' => 'btn btn-success', 'onclick' => 'alert("This will launch the book creation form.\n\nDisabled for this demo!");']) . ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['grid-demo'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('kvgrid', 'Reset Grid')])
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
        'itemLabelSingle' => 'book',
        'itemLabelPlural' => 'books',

        'columns' => [
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
                'attribute'=>'access_level',
                'format'=>'raw',
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