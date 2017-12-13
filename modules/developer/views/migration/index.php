<?php
/* @var $this app\modules\core\components\View */
use app\modules\core\widgets\BoxWidget;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $model app\modules\developer\models\MigrationList */

$this->setSmallTitle('Список');

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

$moduleParam = app()->request->get('module')?['module'=>app()->request->get('module')]:[];
?>
<div class="row">
    <div class="col-sm-12">
<?php
    BoxWidget::begin( [
        'type' => 'info',
        'title' => $this->getSmallTitle()]
    );

    $this->registerCss(<<<CSS

    .grid-view .button-column {
        text-align: center;
        width: auto;
    }

    #data-grid table {
        font-size: 12px;
    }

CSS
    );
?>
    <p>
        <?=Html::a('<i class="fa fa-fw fa-plus-square"></i> Создание миграции', ArrayHelper::merge(['create'], $moduleParam ), ['class'=>'btn btn-sm btn-success'])?>&nbsp;
        <?=count($moduleParam)?Html::a('<i class="fa fa-fw fa-refresh"></i> Обновить БД модуля', ArrayHelper::merge(['refresh'], $moduleParam ), ['class'=>'btn btn-sm btn-info']):''?>
    </p>
<?php
    echo GridView::widget([
        'id'=>'migration-list',
        'dataProvider'=>$model->search(),
        'columns' => [
            [
                'label' =>"Модуль",
                'attribute' => 'module',
                'value'=>function($data){return $data["module"];}
            ],
            [
                'label' =>"Класс",
                'attribute' => 'classname',
                'value'=>function($data){return $data["classname"];}
            ],
            [
                'label' => 'Дата создания',
                'attribute' => 'createtime',
                'value'=> function ($data) {return $data["createtime"]->format("Y-m-d H:i:s");},
            ],
        ],

    ]);

    BoxWidget::end();
?>
    </div>
</div>





