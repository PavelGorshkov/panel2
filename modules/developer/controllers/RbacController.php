<?php

namespace app\modules\developer\controllers;

use app\modules\core\components\WebController;
use app\modules\developer\auth\CreateAuthTask;
use app\modules\developer\controllers\actions\createItemModuleAction;
use app\modules\developer\controllers\actions\viewItemsModuleAction;
use app\modules\developer\forms\AuthTaskFormModel;
use app\modules\developer\models\SearchAuthTask;
use yii\filters\AccessControl;


/**
 * Class RbacController
 * @package app\modules\developer\controllers
 */
class RbacController extends WebController
{
    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
                'class'=>viewItemsModuleAction::class,
                'searchModel'=>SearchAuthTask::class,
            ],
            'create'=>[
                'class'=>createItemModuleAction::class,
                'model'=>AuthTaskFormModel::class,
            ],
        ];
    }
}