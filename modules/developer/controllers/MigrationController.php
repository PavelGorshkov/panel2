<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 13.12.2017
 * Time: 11:39
 */

namespace app\modules\developer\controllers;

use app\modules\core\components\WebController;
use app\modules\developer\auth\MigrationTask;
use yii\filters\AccessControl;

class MigrationController extends WebController {

    public function behaviors() {

        return [
            /**
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => [MigrationTask::OPERATION_READ],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => [MigrationTask::OPERATION_CREATE],
                    ],
                    [
                        'actions' => ['refresh'],
                        'allow' => true,
                        'roles' => [MigrationTask::OPERATION_REFRESH],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
             */
        ];
    }


    public function beforeAction($action) {

        $this->setTitle('Миграции');

        return parent::beforeAction($action);
    }


    public function actions() {

        return [
            'index'=>[
                'class'=>'\app\modules\developer\controllers\actions\viewModelModuleAction',
                'model'=>'\app\modules\developer\models\MigrationList',
            ],
        ];
    }

}