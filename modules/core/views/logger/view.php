<?php
/**
 * @var $this  View
 * @var $model \app\modules\core\models\LogDataFormModel
 * @var $dataProvider ArrayDataProvider
 */

use app\modules\core\components\View;
use app\modules\core\widgets\CustomGridView;
use yii\data\ArrayDataProvider;

?>
<div class="row">
    <div class="col-sm-12">
        <?php
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
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'hover' => true,
            'panel' => [
                'type' => \kartik\grid\GridView::TYPE_SUCCESS,
                'heading' => $this->getSmallTitle(),
            ],
            'persistResize' => false,
            'columns' => [
                [
                    'attribute' => 'date',
                    'format' => 'raw',
                    'contentOptions' => ['class' => 'kv-align-middle'],
                    'headerOptions' => ['class' => 'kv-align-middle'],
                ],
                [
                    'attribute' => 'ip',
                    'format' => 'raw',
                    'contentOptions' => ['class' => 'kv-align-middle'],
                    'headerOptions' => ['class' => 'kv-align-middle'],
                ],
                [
                    'attribute' => 'level',
                    'format' => 'raw',
                    'contentOptions' => ['class' => 'kv-align-middle'],
                    'headerOptions' => ['class' => 'kv-align-middle'],
                    'filter' => $model->getLevelTypes(),
                ],
                [
                    'attribute' => 'message',
                    'format' => 'raw',
                    'contentOptions' => ['class' => 'kv-align-middle'],
                    'headerOptions' => ['class' => 'kv-align-middle'],
                ],
            ]
        ]);
        ?>
    </div>
</div>
