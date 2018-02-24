<?php
/**
 * @var $this  View
 * @var $model LogSourceFormModel
 * @var $dataProvider ArrayDataProvider
 */

use app\modules\core\components\View;
use app\modules\core\helpers\RouterUrlHelper;
use app\modules\core\models\LogSourceFormModel;
use app\modules\core\widgets\CustomActionColumn;
use app\modules\core\widgets\CustomGridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

try {
    echo CustomGridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $model,
        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'export' => [
            'fontAwesome' => true
        ],
        'bordered' => true,
        'striped' => false,
        'condensed' => true,
        'responsive' => false,
        'hover' => true,
        'panel' => [
            'type' => \kartik\grid\GridView::TYPE_SUCCESS,
            'heading' => $this->getSmallTitle(),
        ],
        'persistResize' => false,
        'columns' => [
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model) {
                    return user()->can(RouterUrlHelper::to(['view']))
                        ? Html::a($model->name, Url::to(['view', 'name' => $model->name]))
                        : $model->name;
                },
                'contentOptions' => ['class' => 'kv-align-middle'],
                'headerOptions' => ['class' => 'kv-align-middle'],
            ],
            [
                'attribute' => 'source',
                'format' => 'raw',
                'value' => function ($model) {

                    /** @var $model LogSourceFormModel */
                    return $model::getSourceType($model->source);
                },
                'filter' => $model::getSourceType(),
            ],
            [
                'attribute' => 'mtime',
                'format' => 'raw',
                'value' => function ($model) {
                    return date("H-i : Y-m-d", $model->mtime);
                },
                'filter' => false,
                'contentOptions' => ['class' => 'kv-align-middle'],
                'headerOptions' => ['class' => 'kv-align-middle'],
            ],
            [
                'class' => CustomActionColumn::class,
                'template' => '{delete}',
            ]
        ],
    ]);
} catch (Exception $e) {

    echo $e->getMessage();
}