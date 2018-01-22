<?php

namespace app\modules\developer\controllers;

use app\modules\core\components\WebController;
use app\modules\developer\auth\CreateAuthTask;
use app\modules\developer\controllers\actions\createItemModuleAction;
use app\modules\developer\controllers\actions\viewItemsModuleAction;
use app\modules\developer\forms\MigrationFormModel;
use app\modules\developer\models\SearchAuthTask;
use yii\filters\AccessControl;


/**
 * Class RbacController
 * @package app\modules\developer\controllers
 */
class RbacController extends WebController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => CreateAuthTask::createRulesController()
            ],
        ];
    }


    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action) {

        $this->setTitle('Классы авторизации');

        return parent::beforeAction($action);
    }


    /**
     * @inheritdoc
     * @return array
     */
    public function actions() {

        return [
            'index'=>[
                'class'=>viewItemsModuleAction::className(),
                'searchModel'=>SearchAuthTask::className(),
            ],
            'create'=>[
                'class'=>createItemModuleAction::className(),
                'model'=>MigrationFormModel::className(),
            ],
        ];
    }
}