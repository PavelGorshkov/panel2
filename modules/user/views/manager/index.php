<?php
/* @var $this  View */
/* @var $searchModel SearchUser */

/* @var $dataProvider ActiveDataProvider */

use app\modules\core\components\View;
use app\modules\core\helpers\RouterUrlHelper;
use app\modules\core\widgets\CustomActionColumn;
use app\modules\core\widgets\CustomGridView;
use app\modules\user\auth\ManagerTask;
use app\modules\user\helpers\RegisterFromHelper;
use app\modules\user\helpers\UserAccessLevelHelper;
use app\modules\user\helpers\UserStatusHelper;
use app\modules\user\models\SearchUser;
use app\modules\user\models\ManagerUser;
use kartik\editable\Editable;
use kartik\grid\GridView;
use kartik\popover\PopoverX;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

try {
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
                'headerOptions' => ['class\' => \'kv-align-middle'],
            ],
            [
                'header' => 'Информация',
                'attribute' => 'info',
                'format' => 'raw',
                'value' => function ($model) {

                    /** @var $model ManagerUser */
                    $text = [
                        $model->profile->full_name ?? null,
                        Html::a($model->email, "mailto:" . $model->email),
                    ];

                    if (!empty($model->profile->phone)) {

                        $text[] = $model->profile->phone;
                    }

                    return implode('<br />', $text);
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
                        'data' => UserAccessLevelHelper::getListUFRole(),
                        'submitButton' => [
                            'icon' => '<i class="fa fa-fw fa-check"></i>',
                            'label' => 'Применить',
                            'class' => 'btn btn-sm btn-primary',
                        ],
                        'formOptions' => ['action' => Url::to('access-level')]
                    ];
                },
                'value' => function ($model) {

                    /** @var $model ManagerUser */
                    return UserAccessLevelHelper::getUFRole($model);
                },
                'filter' => UserAccessLevelHelper::getListUFRole(),
            ],
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'status',
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'format' => 'raw',
                'readonly' => function (ManagerUser $model) {

                    return UserAccessLevelHelper::isAdmin($model);
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
                        'formOptions' => ['action' => Url::to(['status'])]
                    ];
                },
                'value' => function ($model) {

                    /** @var $model ManagerUser */
                    return UserStatusHelper::getValue($model->status, true);
                },
                'filter' => UserStatusHelper::getList(),
            ],
            [
                'class' => CustomActionColumn::class,
                'template' => '{access}&nbsp;{update}&nbsp;{password}&nbsp;{delete}',
                'hAlign' => 'right',
                'width' => '150px',
                //'headerOptions' => ['class' => 'col-xs-2'],
                'buttons' => [
                    'access' => function ($url, $model) {

                        /* @var $model ManagerUser */
                        if (
                            UserAccessLevelHelper::isUFRole($model)
                            && user()->can(ManagerTask::OPERATION_ACCESS)
                        ) {

                            return Html::a(
                                '<i class="fa fa-user"></i>',
                                $url,
                                [
                                    'class' => 'btn btn-xs btn-success',
                                    'data-pjax' => 0,
                                    'aria-label' => 'Определить права доступа',
                                    'title' => 'Определить права доступа'
                                ]
                            );
                        } else {

                            return '';
                        }
                    },
                    'password' => function ($url, $model) {

                        /* @var $model ManagerUser */
                        if (
                            $model->registered_from !== RegisterFromHelper::LDAP
                            && user()->can(ManagerTask::OPERATION_UPDATE)
                        ) {

                            return Html::a(
                                '<i class="fa fa-lock"></i>',
                                $url,
                                [
                                    'class' => 'btn btn-xs btn-warning',
                                    'data-pjax' => 0,
                                    'aria-label' => 'Изменить пароль',
                                    'title' => 'Изменить пароль'
                                ]
                            );

                        } else {

                            return '';
                        }
                    }
                ],
            ]
        ]
    ]);
} catch (Exception $e) {

    printr($e);
    echo $e->getMessage();
}
