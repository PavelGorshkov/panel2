<?php

namespace app\modules\finance\components;

use app\modules\finance\helpers\Dictionary;
use app\modules\finance\interfaces\{
    FinanceChartInterface, FinanceObserverInterface
};
use app\modules\finance\models\Balance;

/**
 * Class ObserverBalance
 * @package app\modules\finance\components
 */
class ObserverBalance implements FinanceObserverInterface, FinanceChartInterface
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
    private $_measure = 'руб.';


    /**
     * Получение конфига блока tooltip для hightcharts
     * @return array
     */
    public function getTooltip(): array
    {
        return [
            'valueSuffix' => ' тыс. ' . $this->_measure
        ];
    }


    /**
     * Получение конфига блока xExis для hightcharts
     * @return array
     */
    public function getAxisX(): array
    {
        return [
            'visible' => false
        ];
    }


    /**
     * Получение конфига блока yExis для hightcharts
     * @return array
     */
    public function getAxisY(): array
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
     * Получение типа графика
     * @return string
     */
    public function getType(): string
    {
        return 'spline';
    }


    /**
     * Получение данных для графика.
     * @return array
     */
    public function getDrilldown(): array
    {
        return $this->_chartDrilldownData;
    }


    /**
     * Получение id графика
     * @return string
     */
    public function getChartId(): string
    {
        return 'balance';
    }


    /**
     * Получение заголовка графика
     * @return string
     */
    public function getChartTitle(): string
    {
        return $this->getTitle();
    }


    /**
     * Получение данных для графика.
     * @return array
     */
    public function getSeries(): array
    {
        return $this->_chartData;
    }


    /**
     * Получение заголовка модели для вьюхи
     * @return string
     */
    public function getTitle(): string
    {
        return 'Финансовое обеспечение';
    }


    /**
     * Получение данных для таблицы под графиком
     * @return array
     */
    public function getTableData(): array
    {
        return $this->_tableData;
    }


    /**
     * Получение актуальных годов для модели относительно текущего
     * @param $year
     * @return array возвращает максимальный, минимальный и текущий
     */
    public function getActualYears($year): array
    {
        return Balance::rangeYear($year);
    }


    /**
     * Получение данных для отображения
     * @param string $start
     * @param string $finish
     * @param bool $isPreview
     * @throws \yii\base\InvalidConfigException
     */
    public function createObserverData($start, $finish, $isPreview = false): void
    {
        $this->_getData($start, $finish);

        //Расчет данных для графика hightcharts (series и drilldown)
        $this->_createChartData();

        //Расчет данных для таблицы под графиком
        $this->_createTableData();

        //Расчёт даты начала и конца данных выборки
        $this->_createActualDate();

        if (!$isPreview) {
            //Расчёт данных для pivot
            $this->_createPivotData();
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
                    $tmpSeries['data'][] = [
                        'name' => $kvd . ': ' . $lineConfig[0]['name'],
                        'color' => $lineConfig[0]['color'],
                        'y' => (int)$item['begin_value']
                    ];

                    //Конец дня
                    $tmpSeries['data'][] = [
                        'name' => $kvd . ': ' . $lineConfig[1]['name'],
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
                    'class' => 'info text-center',
                    'label' => '<b>Дата: ' . $date . '</b>'
                ],
                [
                    'class' => 'success text-center',
                    'label' => '<b>Начало дня: ' . round($price['begin_value'] / 1000, 3) . ' тыс.руб.</b>'
                ],
                [
                    'class' => 'danger text-center',
                    'label' => '<b>Конец дня: ' . round($price['end_value'] / 1000, 3) . ' тыс.руб.</b>'
                ]
            ];
        }
    }


    /**
     * Получение состояния индикатора. Отображается над графиком.
     * @return string
     */
    public function getIndicatorLabel(): string
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
     * получение данных для pivot
     * @return array
     */
    public function getPivotData(): array
    {
        return $this->_pivotData;
    }


    /**
     * Получение начальной даты отсчета относительно данных выборки
     * @return string
     */
    public function getActualStart(): string
    {
        return $this->_start;
    }


    /**
     * Получение последней даты отсчета относительно данных выборки
     * @return string
     */
    public function getActualFinish(): string
    {
        return $this->_finish;
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
     * Расчёт данных для pivot
     */
    private function _createPivotData()
    {
        if ($this->_data) {
            foreach ($this->_data['data'] as $day => $dayData) {
                foreach ($dayData as $kvd => $item) {
                    $this->_pivotData[] = [
                        'Дата' => $day,
                        'КВД' => Dictionary::getItem(Dictionary::DICTIONARY_KVD, $kvd)['value'],
                        //'Начало' => (float)$item['begin_value'],
                        'Конец' => (float)$item['end_value']
                    ];
                }
            }
        }
    }

    /**
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function renderData($start, $finish)
    {
        return $this->createObserverData($start, $finish);
    }
}