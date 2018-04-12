<?php

namespace app\modules\finance\controllers;

use app\modules\finance\components\ObserverBalance;
use app\modules\finance\controllers\actions\ViewIndicatorAction;
use \app\modules\core\components\WebController;


/**
* Class FinanceController* @package  \app\modules\finance\controllers*/
class FinanceController extends WebController{

    /**
     * @return array
     */
    public function actions(){
        return [
            'balance' =>[
                'class'=>ViewIndicatorAction::class,
                'model'=>ObserverBalance::class
            ],
        ];
    }


    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action){

        $this->setTitle('Финансовое обеспечение');
        return parent::beforeAction($action);
    }


    /**
     * @return string
     */
    public function actionIndex(){
        //Список экшенов-превьюх финансовых показателей для отображения на индексной странице
        $actionList = array_keys($this->actions());

        //Пока без прав и ролей

        $content = [];
        foreach($actionList as $action){
            $content[$action] = $this->run($action, ['preview' => true]);
        }

        return $this->render('index', ['content' => $content]);
    }


    /**
     * @param $year
     * @param null $start
     * @param null $finish
     * @return array
     */
    public function defineRange($year, $start = null, $finish = null) {

        if ($start === null || $finish === null ) {
            $start = $year.'-01-01';
            if ($year != date('Y')) {
                $finish = $year.'-12-31';
            } else {
                $finish = date('Y-m-d', mktime(0,0,0,date('m'),date('d'),$year));
            }
        }

        return [$start, $finish];
    }



    /**
     * @return array
     */
    /*public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => ArrayHelper::merge(
                    FinanceTask::createRulesController(),
                    [
                        [
                            'allow' => true,
                            'actions' => ['balance'],
                            'roles' => [FinanceTask::OPERATION_READ],
                        ],
                    ])
            ],
        ];
    }*/



}
