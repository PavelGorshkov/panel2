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
use yii\web\HttpException;

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


    public function actionRefresh($module) {

        $modules = app()->moduleManager->getKeysEnabledModules();

        if (!in_array($module, $modules)) {

            throw new HttpException(500, sprintf('Модуль "%s" не найден в активных модулях!', $module));
        }

        ob_start();

        app()->migrator->updateToLatestModule($module);
        $logs = ob_get_contents();
        ob_end_clean();

        return $this->render($this->action->id, [
           'module'=>$module,
            'logs'=>$logs,
        ]);
    }

}