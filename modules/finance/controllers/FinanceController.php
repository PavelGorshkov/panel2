<?php

namespace app\modules\finance\controllers;

use app\modules\core\components\actions\ObserverAction;
use app\modules\finance\auth\FinanceTask;
use app\modules\finance\components\ObserverBalance;
use app\modules\finance\controllers\actions\ViewIndicatorAction;
use \app\modules\core\components\WebController;
use yii\filters\AccessControl;

/**
 * Class FinanceController
 * @package  \app\modules\finance\controllers
 */
class FinanceController extends WebController
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
                'rules' => FinanceTask::createRulesController(),
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => ObserverAction::class,
                'smallTitle' => 'Финансовые показатели',
                'actions' => [
                    'balance/lastday' => 'Остатки на счетах',
                    'balance/graph' => 'Динамика изменения'
                ]
            ]
        ];
    }


    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->setTitle('Финансовое обеспечение');

        return parent::beforeAction($action);
    }
}
