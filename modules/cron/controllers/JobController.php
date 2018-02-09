<?php

namespace app\modules\cron\controllers;

use app\modules\core\components\actions\GridViewAction;
use app\modules\core\components\actions\SaveModelAction;
use app\modules\core\components\WebController;
use app\modules\core\components\ConsoleRunner;
use app\modules\cron\forms\JobScheduleFormModel;
use app\modules\cron\helpers\JobStatusListHelper;
use app\modules\cron\models\Job;
use app\modules\cron\models\RunnerJob;
use app\modules\cron\models\SearchJob;
use kartik\grid\EditableColumnAction;
use app\modules\cron\auth\JobTask;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

/**
 * Class ManagerController
 * @package app\modules\user\controllers
 */
class JobController extends WebController {

    /**
     * @return array
     */
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => ArrayHelper::merge(
                    JobTask::createRulesController(),
                    [
                        [
                            'allow' => true,
                            'actions' => ['is_active', 'run'],
                            'roles' => [JobTask::OPERATION_UPDATE],
                        ],
                    ])
            ],
        ];
    }


    /**
     * @return array
     */
    public function actions(){
        return [
            'index' =>[
                'class'=>GridViewAction::className(),
                'searchModel'=>SearchJob::className(),
                'smallTitle'=>'Список',
            ],
            'create'=>[
                'class'=>SaveModelAction::className(),
                'modelForm'=>JobScheduleFormModel::className(),
                'model'=>Job::className(),
                'view'=>'edit',
                'isNewRecord'=>true,
            ],
            'update'=>[
                'class'=>SaveModelAction::className(),
                'modelForm'=>JobScheduleFormModel::className(),
                'model'=>Job::className(),
                'view'=>'edit',
                'isNewRecord'=>false,
            ],
            'is_active' => [
                'class' => EditableColumnAction::className(),
                'modelClass' => Job::className(),
                'outputValue' => function($model) {
                    return JobStatusListHelper::getList()[$model->is_active];
                },
            ]
        ];
    }


    /**
     * Запуск задания на выполнение
     * @param $id
     * @throws \yii\base\ExitException
     */
    public function actionRun($id) {
        $job = RunnerJob::findOne($id);
        list($commandStatus, $jobOut) = $job->runJob();

        $commandStatus === ConsoleRunner::STREAM_STATUS_SUCCESS
            ? user()->setSuccessFlash('Команда '.$job->command.' была успешно выполнена. '.$jobOut)
            : user()->setErrorFlash('Во время работы команды '.$job->command.' произошла ошибка. '.$jobOut);

        $this->redirect('index');
        app()->end();
    }


    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action){
        $this->setTitle('Управление заданиями планировщика');
        return parent::beforeAction($action);
    }
}