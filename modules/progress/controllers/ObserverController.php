<?php

namespace app\modules\progress\controllers;

use app\modules\progress\auth\ProgressTask;
use app\modules\progress\models\Observer;
use yii\filters\AccessControl;
use \app\modules\core\components\WebController;
use yii\helpers\ArrayHelper;

/**
 * Class ObserverController
 * @package app\modules\progress\controllers
 */
class ObserverController extends WebController{

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action){
        $this->setTitle('Успеваемость');
        $this->setSmallTitle('Статистика');
        return parent::beforeAction($action);
    }


    /**
     * @return string
     */
    public function actionIndex(){
        $observer = new Observer();
        list($min, $max, $year) = $observer->getRangeYear(app()->request->get('year'));

        return $this->render('index', [
            'min' => $min,
            'max' => $max,
            'year' => $year,
            'model' => $observer
        ]);
    }


    /**
     * Просмотр статистики по факультету
     * @return string
     */
    public function actionUnit(){
        $year = app()->request->post('year');
        $form = app()->request->post('form');

        $observer = new Observer();
        return $this->renderPartial('_unit', [
            'data' => $observer->getUnitStatistic($year, $form),
            'year' => $year,
            'form' => $form
        ]);
    }


    /**
     * Просмотр статистики по группе
     * @return string
     */
    public function actionGroup(){
        $year = app()->request->post('year');
        $form = app()->request->post('form');
        $unit = app()->request->post('unit');

        $observer = new Observer();
        return $this->renderPartial('_group', [
            'data' => $observer->getGroupStatistic($year, $form, $unit),
            'year' => $year,
            'form' => $form,
            'unit' => $unit
        ]);
    }


    /**
     * @return array
     */
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => ArrayHelper::merge(ProgressTask::createRulesController(),
                    [
                        [
                            'allow' => true,
                            'actions' => ['balance', 'unit', 'group'],
                            'roles' => [ProgressTask::OPERATION_READ],
                        ],
                    ]
                )
            ],
        ];
    }
}
