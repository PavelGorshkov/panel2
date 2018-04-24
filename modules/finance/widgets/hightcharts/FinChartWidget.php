<?php

namespace app\modules\finance\widgets\hightcharts;

use app\modules\finance\interfaces\FinanceChartInterface;
use yii\base\Widget;
use yii\db\Exception;
use yii\web\ServerErrorHttpException;

/**
 * Class ChartWidget
 * @package app\modules\finance\widgets\hightcharts
 */
class FinChartWidget extends Widget
{
    /* @var FinanceChartInterface|null */
    public $model = null;

    /* @var string */
    protected $view = 'chart';


    /**
     * Initializes the object.
     */
    public function init()
    {
        parent::init();

        if (empty($this->model)) {

            throw new ServerErrorHttpException('Empty data for FinChartWidget');
        }

        if (!($this->model instanceof FinanceChartInterface)) {

            throw new Exception('Model in FinChartWidget not implements FinanceChartInterface', 500);
        }
    }


    /**
     * @return string
     */
    public function run()
    {
        return $this->render($this->view, [

                'model' => $this->model,
                'series' => json_encode($this->model->getSeries()),
                'drilldown' => json_encode($this->model->getDrilldown()),
                'tooltip' => json_encode($this->model->getTooltip()),
                'xAxis' => json_encode($this->model->getAxisX()),
                'yAxis' => json_encode($this->model->getAxisY()),
                'id' => 'chart_' . $this->model->getChartId()
            ]
        );
    }
}