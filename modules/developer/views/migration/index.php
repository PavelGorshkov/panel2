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
        'url' => ['index'],
        'encode' => false,
    ],
    [
        'label' => $this->getSmallTitle(),
        'encode' => false,
    ]
]);

$moduleParam = app()->request->get('module') ? ['module' => app()->request->get('module')] : [];
?>
<div class="row">
    <div class="col-sm-12">
        <?php
        /*
        echo CustomGridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => true,
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'toolbar' => [
                ['content' =>
                    Html::a(
                        '<i class="glyphicon glyphicon-plus"></i>',
                        ['create'],
                        [
                            'title' => 'Добавить пользователя',
                            'class' => 'btn btn-success',
                        ]
                    )
                    . ' ' .
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
                    'value' => function ($model) {

                        return user()->can(RouterUrlHelper::to(['update']))
                            ? Html::a($model->username, Url::to(['update', 'id' => $model->id]))
                            : $model->username;
                    },
                    'contentOptions' => ['class' => 'kv-align-middle'],
                    'headerOptions' => ['class' => 'kv-align-middle'],
                ],
                [
                    'header' => 'Информация',
                    'attribute' => 'info',
                    'format' => 'raw',
                    'value' => function ($model) {

                        /** @var $model ManagerUser *
                        return $model->getContact();
                    }
                ],
                [
                    'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'access_level',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'format' => 'raw',
                    'readonly' => function (ManagerUser $model) {

                        return $model->id === user()->id;
                    },
                    'editableOptions' => function () { //$model, $key, $index
                        return [
                            'size' => PopoverX::SIZE_MEDIUM,
                            'inputType' => Editable::INPUT_DROPDOWN_LIST,
                            'placement' => PopoverX::ALIGN_TOP,
                            'data' => ManagerUser::getAccessLevelList(),
                            'submitButton' => [
                                'icon' => '<i class="fa fa-fw fa-check"></i>',
                                'label' => 'Применить',
                                'class' => 'btn btn-sm btn-primary',
                            ],
                            'formOptions' => ['action' => Url::to('access-level')]
                        ];
                    },
                    'value' => function ($model) {

                        /** @var $model ManagerUser *
                        return $model->getAccessGroup();
                    },
                    'filter' => ManagerUser::getAccessLevelList(),
                ],
                [
                    'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'status',
                    'hAlign' => 'center',
                    'vAlign' => 'middle',
                    'format' => 'raw',
                    'readonly' => function (ManagerUser $model) {

                        return $model->isAdmin();
                    },
                    'editableOptions' => function () { //$model, $key, $index
                        return [
                            'size' => PopoverX::SIZE_MEDIUM,
                            'inputType' => Editable::INPUT_DROPDOWN_LIST,
                            'data' => UserStatusHelper::getList(),
                            'placement' => PopoverX::ALIGN_TOP,
                            'submitButton' => [
                                'icon' => '<i class="fa fa-fw fa-check"></i>',
                                'label' => 'Применить',
                                'class' => 'btn btn-sm btn-primary',
                            ],
                            'formOptions' => ['action' => Url::to('status')]
                        ];
                    },
                    'value' => function ($model) {

                        /** @var $model ManagerUser *
                        return UserStatusHelper::getValue($model->status, true);
                    },
                    'filter' => UserStatusHelper::getList(),
                ],
                [
                    'class' => CustomActionColumn::className(),
                    'template' => '{access} {update} {password} {delete}',
                    'buttons' => [
                        ''
                    ],
                ]

            ]
        ]);
*/

        BoxWidget::begin([
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
            <?= Html::a('<i class="fa fa-fw fa-plus-square"></i> Создание миграции', ArrayHelper::merge(['create'], $moduleParam), ['class' => 'btn btn-sm btn-success']) ?>
            &nbsp;
            <?= count($moduleParam) ? Html::a('<i class="fa fa-fw fa-refresh"></i> Обновить БД модуля', ArrayHelper::merge(['refresh'], $moduleParam), ['class' => 'btn btn-sm btn-info']) : '' ?>
        </p>
        <?php
        echo GridView::widget([
            'id' => 'migration-list',
            'dataProvider' => $model->search(),
            'columns' => [
                [
                    'label' => "Модуль",
                    'attribute' => 'module',
                    'value' => function ($data) {
                        return $data["module"];
                    }
                ],
                [
                    'label' => "Класс",
                    'attribute' => 'classname',
                    'value' => function ($data) {
                        return $data["classname"];
                    }
                ],
                [
                    'label' => 'Дата создания',
                    'attribute' => 'createtime',
                    'value' => function ($data) {
                        /* @var $date \DateTime */
                        $date = $data['createtime'];

                        return $date->format("Y-m-d H:i:s");
                    },
                ],
            ],

        ]);

        BoxWidget::end();
        ?>
    </div>
</div>





