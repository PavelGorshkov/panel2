<?php

namespace app\modules\rating\controllers;

use app\modules\core\components\actions\GridViewAction;
use app\modules\core\components\actions\SaveModelAction;
use yii\filters\AccessControl;
use app\modules\core\components\WebController;
use yii\filters\VerbFilter;
use app\modules\rating\models\Period;
use app\modules\rating\models\search\PeriodSearch;
use app\modules\rating\forms\PeriodForm;
use app\modules\rating\auth\PeriodTask;
use yii\web\NotFoundHttpException;

/**
 * PeriodController implements the CRUD actions for Period model.
 *
 * Class ManagerController
 * @package app\modules\user\controllers
 */
class PeriodController extends WebController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => PeriodTask::createRulesController(),
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
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
        $this->setTitle('Периоды');

        return parent::beforeAction($action);
    }


    /**
     * @return array
     */
    public function actions()
    {
        return [
            'index' =>[
                'class'=>GridViewAction::class,
                'searchModel'=>PeriodSearch::class,
                'smallTitle'=>'Список периодов',
            ],
            'create'=>[
                'class'=>SaveModelAction::class,
                'modelForm'=>PeriodForm::class,
                'model'=>Period::class,
                'isNewRecord'=>true,
            ],
            'update'=>[
                'class'=>SaveModelAction::class,
                'modelForm'=>PeriodForm::class,
                'model'=>Period::class,
                'isNewRecord'=>false,
            ],
        ];
    }


    /**
     * Deletes an existing Period model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {
        //TODO реализуйте метод удаления данных
        //$this->findModel()->delete();

        //return $this->redirect(['index']);
    }
}
