<?php

namespace app\modules\finance\widgets\hightcharts;

use app\modules\finance\interfaces\FinanceChartInterface;
use yii\base\Widget;
use yii\db\Exception;

/**
 * Class ChartWidget
 * @package app\modules\finance\widgets\hightcharts
 */
class ChartWidget extends Widget{

    /* @var FinanceChartInterface|null */
    public $model = null;

    /* @var string */
    protected $view = 'chart';


    /**
     * Initializes the object.
     */
    public function init(){
        parent::init();

        if($this->model === null || !($this->model instanceof FinanceChartInterface)){
            throw new Exception('Empty data.', 500);
        }
    }


    /**
     * @return string
     */
    public function run() {
        return $this->render($this->view, [
                'model' => $this->model,
                'series' => json_encode($this->model->getSeries()),
                'drilldown' => json_encode($this->model->getDrilldown()),
                'tooltip' => json_encode($this->model->getTooltip()),
                'xAxis' => json_encode($this->model->getAxisX()),
                'yAxis' => json_encode($this->model->getAxisY()),
                'id' => 'chart_'.$this->model->getChartId()
            ]
        );
    }
}