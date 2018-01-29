<?php

use yii\helpers\StringHelper;



/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $modelAlias = $modelClass . 'Model';
}

echo "<?php\n";
?>

/* @var $this  View */
/* @var $searchModel  <?= isset($modelAlias)?$modelAlias:$searchModelClass ?>*/
/* @var $dataProvider DataProviderInterface */

use yii\helpers\Html;
use app\modules\core\components\View;
use yii\data\DataProviderInterface;
use <?= ltrim($generator->searchModelClass, '\\')?>;
use app\modules\core\widgets\CustomGridView;


echo CustomGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'pjax' => true,
    'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
    'toolbar' =>  [
    /*  ['content' =>
             Html::a(
                 '<i class="glyphicon glyphicon-plus"></i>',
                ['create'],
                [
                    'title' => 'Добавить',
                    'class' => 'btn btn-success',
                ]
             )
        ], */
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

    ]
]);