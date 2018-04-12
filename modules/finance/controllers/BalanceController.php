<?php

namespace app\modules\finance\controllers;

use app\modules\core\components\actions\ObserverAction;
use app\modules\core\components\WebController;
use app\modules\finance\auth\BalanceTask;
use app\modules\finance\interfaces\RangeDateInterface;
use app\modules\finance\models\Balance;
use Yii;
use yii\base\Action;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;

/**
 * Class BalanceController
 * @package app\modules\finance\controllers
 */
class BalanceController extends WebController
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
                'rules' => BalanceTask::createRulesController(),
            ],
        ];
    }


    /**
     * @inheritdoc
     * @param Action $action
     * @return bool
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->setTitle('Финансовые остатки');

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
                'class' => ObserverAction::class,
                'smallTitle' => 'Финансовые остатки',
                'actions' => [
                    'balance/lastday' => 'Остатки на счетах',
                    'balance/graph' => 'Динамика изменения'
                ]
            ]
        ];
    }


    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionGraph()
    {
        /** @var $model RangeDateInterface */
        $model = Yii::createObject([
            'class' => Balance::class
        ]);

        $params = app()->request->get($this->action->id, []);

        $year = $params['year']??date('Y');
        $start = $params['start']??null;
        $finish = $params['finish']??null;

        list($min, $max, $current) = $model->getActualYears($year);

        list($start, $finish) = $this->defineRange($current, $start, $finish);

        $renderParams = [
            'model' => $model,
            'widgetYearData' => [
                'minYear' => $min,
                'current' => $current,
                'maxYear' => $max,
                'action'  => $this->action->id,
            ],
            'widgetRangeData' => [
                'start' => $start,
                'finish' => $finish,
                'year'   => $current,
                'action' => $this->action->id,
            ],
            'action' =>  $this->action->id,
        ];

        return $this->render('graph', $renderParams);
    }


    /**
     * @param $year
     * @param null $start
     * @param null $finish
     * @return array
     */
    protected function defineRange($year, $start = null, $finish = null)
    {
        if ($start === null || $finish === null) {

            $start = $year . '-01-01';

            if ($year != date('Y')) {

                $finish = $year . '-12-31';

            } else {

                $finish = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), $year));
            }
        }

        return [$start, $finish];
    }

}