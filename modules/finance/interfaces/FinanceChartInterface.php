<?php

namespace app\modules\finance\interfaces;

/**
 * Interface FinanceChartInterface
 * @package app\modules\finance\interfaces
 */
interface FinanceChartInterface
{
    /**
     * Получение последней даты отсчета относительно данных выборки
     * @return string
     */
    public function getActualFinish();


    /**
     * Получение начальной даты отсчета относительно данных выборки
     * @return string
     */
    public function getActualStart();


    /**
     * Получение конфига блока xExis для hightcharts
     * @return array
     */
    public function getAxisX();


    /**
     * Получение конфига блока yExis для hightcharts
     * @return array
     */
    public function getAxisY();


    /**
     * Получение id графика
     * @return string
     */
    public function getChartId();


    /**
     * Получение заголовка графика
     * @return string
     */
    public function getChartTitle();


    /**
     * Получение конфига блока drilldown для hightcharts
     * @return array
     */
    public function getDrilldown();


    /**
     * Получение состояния индикатора. Отображается над графиком.
     * @return string
     */
    public function getIndicatorLabel();


    /**
     * Получение конфига блока series для hightcharts
     * @return array
     */
    public function getSeries();


    /**
     * Получение конфига блока tooltip для hightcharts
     * @return array
     */
    public function getTooltip();


    /**
     * Получение типа графика
     * @return string
     */
    public function getType();


    /**
     * @param string $start
     * @param string $finish
     * @return mixed
     */
    public function renderData($start, $finish);
}