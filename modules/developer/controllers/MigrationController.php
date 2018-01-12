<?php
namespace app\modules\developer\controllers;

use app\modules\core\auth\ModuleTask;
use app\modules\core\components\WebController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

/**
 * Class MigrationController
 * @package app\modules\developer\controllers
 */
class MigrationController extends WebController {

    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors() {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => ModuleTask::createRulesController()
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action) {

        $this->setTitle('Миграции');

        return parent::beforeAction($action);
    }


    /**
     * @inheritdoc
     * @return array
     */
    public function actions() {

        return [
            'index'=>[
                'class'=>'\app\modules\developer\controllers\actions\viewItemsModuleAction',
                'model'=>'\app\modules\developer\models\MigrationList',
            ],
            'create'=>[
                'class'=>'\app\modules\developer\controllers\actions\createItemModuleAction',
                'model'=>'\app\modules\developer\models\MigrationFormModel',
            ]
        ];
    }


    /**
     * @param $module
     * @return string
     * @throws ServerErrorHttpException
     * @throws \yii\base\Exception
     */
    public function actionRefresh($module) {

        $modules = app()->moduleManager->getListEnabledModules();

        if (!in_array($module, $modules)) {

            throw new ServerErrorHttpException(sprintf('Модуль "%s" не найден в активных модулях!', $module));
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