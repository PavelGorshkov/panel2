<?php

namespace app\modules\user\controllers;

use app\modules\core\components\actions\GridViewAction;
use app\modules\core\components\actions\SaveModelAction;
use app\modules\core\components\WebController;
use app\modules\user\auth\ManagerTask;
use app\modules\user\components\Roles;
use app\modules\user\forms\UserFormModel;
use app\modules\user\helpers\UserStatusHelper;
use app\modules\user\models\SearchUser;
use app\modules\user\models\ManagerUser;
use kartik\grid\EditableColumnAction;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class ManagerController extends WebController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => ArrayHelper::merge(
                    ManagerTask::createRulesController(),
                    [
                        [
                            'allow' => true,
                            'actions' => ['test'],
                            'roles' => [Roles::ADMIN],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['access-level', 'status'],
                            'roles' => [ManagerTask::OPERATION_UPDATE],
                        ],
                    ])
            ],
        ];
    }


    public function actions()
    {

        return [
            'index' =>[
                'class'=>GridViewAction::className(),
                'searchModel'=>SearchUser::className(),
                'smallTitle'=>'Список',
            ],
            'update'=>[
                'class'=>SaveModelAction::className(),
                'modelForm'=>UserFormModel::className(),
                'model'=>ManagerUser::className(),
                'isNewRecord'=>false,
            ],
            'access-level' => [
                'class' => EditableColumnAction::className(),
                'modelClass' => ManagerUser::className(),
                'outputValue' => function (ManagerUser $model) {

                   return $model->getAccessGroup();
                },
            ],
            'status' => [
                'class' => EditableColumnAction::className(),
                'modelClass' => ManagerUser::className(),
                'outputValue' => function ($model, $attribute) {

                    return UserStatusHelper::getValue($model->$attribute, true);
                },
            ],
        ];
    }


    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->setTitle('Управление пользователями');

        return parent::beforeAction($action);
    }
}