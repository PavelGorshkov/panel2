<?php

namespace app\modules\finance\controllers\actions;

use app\modules\core\components\actions\WebAction;
use app\modules\finance\interfaces\FinanceObserverInterface;
use yii\web\HttpException;

/**
 * Class ViewIndicatorAction
 * @package app\modules\developer\controllers\actions
 */
class ViewIndicatorAction extends WebAction{

    /**
     * @var FinanceObserverInterface
     */
    public $model = null;


    /**
     * @throws HttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function init(){
        parent::init();

        if ($this->model === null){
            throw new HttpException(500,'In action '.$this->id.' in controller '.$this->controller->id.' not found param model');
        }

        $this->model = \Yii::createObject(['class'=>$this->model]);
        if(!($this->model instanceof FinanceObserverInterface)){
            throw new HttpException(500, 'Bad model');
        }
    }



    public function run($preview = false){

        $params = app()->request->get();

        $year = isset($params['year'])?$params['year']:date('Y');
        $start = isset($params['start'])?$params['start']:null;
        $finish = isset($params['finish'])?$params['finish']:null;

        list($min, $max, $current) = $this->model->getActualYears($year);
        list($start, $finish) = $this->controller->defineRange($current, $start, $finish);

        $renderParams = [
            'model' => $this->model,
            'widgetYearData' => [
                'minYear' => $min,
                'current' => $current,
                'maxYear' => $max
            ],
            'widgetRangeData' => [
                'start' => $start,
                'finish' => $finish
            ],
            'action' =>  $this->id
        ];

        if($preview){
            $this->model->createObserverData($start, $finish, $preview);
            return $this->controller->renderPartial('preview', $renderParams);
        }
        else{
            $this->controller->setSmallTitle($this->model->getTitle());
            $this->model->createObserverData($start, $finish);
            return $this->controller->render($this->view, $renderParams);
        }
    }
}