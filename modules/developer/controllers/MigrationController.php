<?php

namespace app\modules\developer\controllers;

use app\modules\core\components\WebController;
use app\modules\developer\auth\MigrationTask;
use app\modules\developer\controllers\actions\createItemModuleAction;
use app\modules\developer\controllers\actions\viewItemsModuleAction;
use app\modules\developer\forms\MigrationFormModel;
use app\modules\developer\models\SearchMigration;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\ServerErrorHttpException;

/**
 * Class MigrationController
 * @package app\modules\developer\controllers
 */
class MigrationController extends WebController
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
                'rules' => ArrayHelper::merge(MigrationTask::createRulesController(), [
                    [
                        'allow' => true,
                        'actions' => ['refresh-all'],
                        'roles' => [MigrationTask::OPERATION_REFRESH],
                    ],
                ]),
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

        $this->setTitle('Миграции');

        return parent::beforeAction($action);
    }


    /**
     * @inheritdoc
     * @return array
     */
    public function actions()
    {

        return [
            'index' => [
                'class' => viewItemsModuleAction::class,
                'searchModel' => SearchMigration::class,
            ],
            'create' => [
                'class' => createItemModuleAction::class,
                'model' => MigrationFormModel::class,
            ],
        ];
    }


    /**
     * @param $module
     * @return string
     * @throws ServerErrorHttpException
     * @throws \yii\base\Exception
     */
    public function actionRefresh($module)
    {

        $modules = app()->moduleManager->getListEnabledModules();

        if (!in_array($module, $modules)) {

            throw new ServerErrorHttpException(sprintf('Модуль "%s" не найден в активных модулях!', $module));
        }

        app()->migrator->updateToLatestModule($module);

        return $this->render('refresh', [
            'module' => $module,
        ]);
    }


    /**
     * @return string
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRefreshAll()
    {
        $modules = app()->moduleManager->getListEnabledModules();

        foreach ($modules as $module) {

            app()->migrator->updateToLatestModule($module);
        }

        return $this->render('refresh-all');
    }
}