<?php

namespace app\modules\finance\models;

use app\modules\finance\helpers\Dictionary;
use app\modules\finance\interfaces\FinanceChartInterface;
use app\modules\finance\interfaces\RangeDateInterface;
use yii\base\BaseObject;
use yii\db\Expression;

/**
 * Class BalanceGraph
 * @package app\modules\finance\models
 */
class BalanceGraph extends BaseObject implements RangeDateInterface, FinanceChartInterface
{
    /**
     * Обработанные данные из таблицы
     * @var null|array
     */
    private $_data = [];

    /* @var array */
    private $_chartData = [];

    /* @var array */
    private $_chartDrilldownData = [];

    /* @var array */
    private $_tableData = [];

    /* @var array */
    private $_pivotData = [];

    /* @var string */
    private $_start = '';

    /* @var string */
    private $_finish = '';

    /* @var string */
    private $_measure = '₽';

    /**
     * Очистка данных перед расчетом
     */
    private function _clearData()
    {
        $this->_data = [];
        $this->_chartData = [];
        $this->_chartDrilldownData = [];
        $this->_tableData = [];
        $this->_pivotData = [];
        $this->_start = '';
        $this->_finish = '';
    }


    /**
     * Расчёт даты начала и конца данных выборки
     */
    private function _createActualDate()
    {
        if ($this->_data) {

            $days = array_keys($this->_data['days']);

            $this->_start = $days[0];
            $this->_finish = $days[count($days) - 1];
        }
    }


    /**
     * Расчет данных для графика hightcharts (series и drilldown)
     */
    private function _createChartData()
    {
        if ($this->_data) {

            $lineConfig = [
                [
                    'name' => 'Начало дня',
                    'color' => '#33ff33',
                    'lineWidth' => 4,
                    'data' => []
                ],
                [
                    'name' => 'Конец дня',
                    'color' => '#ff3333',
                    'lineWidth' => 1,
                    'data' => []
                ],
                [
                    'name' => 'Разница',
                    'yAxis' => 1,
                    'color' => '#3189c6',
                    'type' => 'spline',
                    'marker' => [
                        'lineWidth' => 2,
                        'radius' => 2,
                        'symbol' => 'circle',
                        'lineColor' => '#3189c6',
                        'fillColor' => 'white',
                        'enabled' => true,
                    ],
                    'tooltip' => [
                        'valueSuffix' => ' ' . $this->_measure,
                    ],
                    'data' => []
                ]
            ];

            //Расчет данных для основного графика
            foreach ($this->_data['total'] as $day => $item) {

                $drilldownId = str_replace('.', '_', $day);

                //Начало дня
                $lineConfig[0]['data'][] = [
                    'name' => $day,
                    'y' => round($item['begin_value'] / 1000, 3),
                    'drilldown' => $drilldownId,
                ];

                //Конец дня
                $lineConfig[1]['data'][] = [
                    'name' => $day,
                    'y' => round($item['end_value'] / 1000, 3),
                    'drilldown' => $drilldownId,
                ];

                //Разница
                $lineConfig[2]['data'][] = [
                    'name' => $day,
                    'y' => round($item['begin_value'] - $item['end_value'], 2),
                    'drilldown' => $drilldownId,
                ];
            }

            $this->_chartData = $lineConfig;

            //Получение данных для drilldown
            $series = [];

            foreach ($this->_data['data'] as $day => $dayItem) {

                $tmpSeries = [
                    'name' => $day,
                    'id' => str_replace('.', '_', $day),
                    'type' => 'column',
                    'dataLabels' => [
                        'enabled' => 'true',
                        'format' => '{point.name}: {point.y:.2f} ' . $this->_measure
                    ],
                    'tooltip' => [
                        'valueSuffix' => ' ' . $this->_measure,
                    ],
                    'data' => []
                ];

                foreach ($dayItem as $kvd => $item) {

                    //Начало дня
                    $kvd = Dictionary::getItem(Dictionary::DICTIONARY_KVD, $kvd);
                    $tmpSeries['data'][] = [
                        'name' => $kvd['value'] . ': ' . $lineConfig[0]['name'],
                        'color' => $lineConfig[0]['color'],
                        'y' => (int)$item['begin_value']
                    ];

                    //Конец дня
                    $tmpSeries['data'][] = [
                        'name' => $kvd['value'] . ': ' . $lineConfig[1]['name'],
                        'color' => $lineConfig[1]['color'],
                        'y' => (int)$item['end_value']
                    ];
                }

                $series[] = $tmpSeries;
            }

            $this->_chartDrilldownData = [
                'series' => $series,
                'legend' => [
                    'enabled' => 'true'
                ],
                'plotOptions' => [
                    'series' => [
                        'borderWidth' => 0,
                        'dataLabels' => [
                            'enabled' => 'true',
                            'format' => '{point.y:.2f}'
                        ]
                    ]
                ]
            ];
        }
    }


    /**
     * Создание данных для таблицы
     */
    private function _createTableData()
    {
        if ($this->_data) {

            end($this->_data['days']);
            $date = key($this->_data['days']);
            $price = $this->_data['total'][$date];

            $this->_tableData = [
                [
                    'class' => 'bg-aqua text-center',
                    'label' => '<b>Дата: ' . $date . '</b>'
                ],
                [
                    'class' => 'bg-green text-center',
                    'label' => '<b>Начало дня: ' . round($price['begin_value'] / 1000, 3) . ' тыс.руб.</b>'
                ],
                [
                    'class' => 'bg-orange text-center',
                    'label' => '<b>Конец дня: ' . round($price['end_value'] / 1000, 3) . ' тыс.руб.</b>'
                ]
            ];
        }
    }


    /**
     * Получение выборки данных из таблицы
     * @param $start
     * @param $finish
     * @throws \yii\base\InvalidConfigException
     */
    private function _getData($start, $finish)
    {
        //Очистка данных перед расчетом
        $this->_clearData();

        $dbData = Balance::find()
            ->where(['between', 'creating_date', $start, $finish])
            ->orderBy('creating_date')
            ->asArray()
            ->all();

        foreach ($dbData as $item) {

            $date = app()->formatter->asDate($item['creating_date'], 'long');
            $this->_data['days'][$date] = $item['creating_date'];

            foreach (['begin_value', 'end_value'] as $type) {

                $this->_data['data'][$date][$item['kvd_id']][$type] = $item[$type];

                if (!isset($this->_data['total'][$date][$type])) {

                    $this->_data['total'][$date][$type] = 0;
                }

                $this->_data['total'][$date][$type] += $item[$type];
            }
        }
    }


    /**
     * Получение начальной даты отсчета относительно данных выборки
     * @return string
     */
    public function getActualStart()
    {
        return $this->_start;
    }


    /**
     * Получение последней даты отсчета относительно данных выборки
     * @return string
     */
    public function getActualFinish()
    {
        return $this->_finish;
    }


    /**
     * @param null $year
     * @return array
     */
    public function getActualYears($year)
    {
        $range = Balance::find()
            ->select([
                new Expression('MAX(YEAR(creating_date)) max_year'),
                new Expression('MIN(YEAR(creating_date)) min_year')
            ])
            ->asArray()
            ->groupBy('YEAR(creating_date)')
            ->one();

        $max = $range['max_year'] ?? (integer)date('Y');
        $min = $range['min_year'] ?? (integer)date('Y');

        $current = (!$year || ($year > $max)) ? $max : $year;

        if (date('n') > 8) {
            $max = $max + 1;
        }

        return [$min, $max, $current];
    }


    /**
     * Получение конфига блока xExis для hightcharts
     * @return array
     */
    public function getAxisX()
    {
        return [
            'visible' => false
        ];
    }

    /**
     * Получение конфига блока yExis для hightcharts
     * @return array
     */
    public function getAxisY()
    {
        return [
            [
                'title' => [
                    'text' => $this->_measure
                ]
            ],
            [
                'title' => [
                    'text' => 'Разность ' . $this->_measure,
                    'style' => [
                        'color' => '#3189c6'
                    ]
                ],
                'labels' => [
                    'format' => '{value} ' . $this->_measure,
                    'style' => [
                        'color' => '#3189c6'
                    ]
                ],
                'opposite' => true
            ]
        ];
    }


    /**
     * Получение id графика
     * @return string
     */
    public function getChartId()
    {
        return 'balance';
    }


    /**
     * Получение заголовка графика
     * @return string
     */
    public function getChartTitle()
    {
        return 'Финансовые остатки';
    }


    /**
     * Получение конфига блока drilldown для hightcharts
     * @return array
     */
    public function getDrilldown()
    {
        return $this->_chartDrilldownData;
    }


    /**
     * Получение состояния индикатора. Отображается над графиком.
     * @return string
     */
    public function getIndicatorLabel()
    {
        $label = '';
        if ($this->_data) {

            end($this->_data['days']);
            //$date = key($this->_data['days']);
            $price = $this->_data['total'][key($this->_data['days'])];

            $ratio = round((1 - $price['end_value'] / $price['begin_value']) * 100, 2);

            if ($ratio == 0) {

                $text = '0%';

            } elseif ($ratio > 0) {

                $text = '<span style="color: #00ff00;">' . $ratio . '% &uarr;</span>';

            } else {

                $text = '<span style="color: #ff0000;">' . abs($ratio) . '% &darr;</span>';
            }

            $label = '<h5><span style="color:#3189C6">Показатель:</span> ' . $text . '</h5>';
        }

        return $label;
    }


    /**
     * Получение конфига блока series для hightcharts
     * @return array
     */
    public function getSeries()
    {
        return $this->_chartData;
    }


    /**
     * Получение данных для таблицы под графиком
     * @return array
     */
    public function getTableData()
    {
        return $this->_tableData;
    }


    /**
     * Получение конфига блока tooltip для hightcharts
     * @return array
     */
    public function getTooltip()
    {
        return [
            'valueSuffix' => ' тыс. ' . $this->_measure
        ];
    }


    /**
     * Получение типа графика
     * @return string
     */
    public function getType()
    {
        return 'spline';
    }


    /**
     * @param string $start
     * @param string $finish
     * @return mixed|void
     * @throws \yii\base\InvalidConfigException
     */
    public function renderData($start, $finish)
    {
        $this->_getData($start, $finish);

        //Расчет данных для графика hightcharts (series и drilldown)
        $this->_createChartData();

        //Расчет данных для таблицы под графиком
        $this->_createTableData();

        //Расчёт даты начала и конца данных выборки
        $this->_createActualDate();
    }

}