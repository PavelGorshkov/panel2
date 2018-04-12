<?php

namespace app\modules\finance\interfaces;

/**
 * Interface FinanceChartInterface
 * @package app\modules\finance\interfaces
 */
interface FinanceChartInterface{

    /**
     * Получение типа графика
     * @return string
     */
    public function getType() : string;


    /**
     * Получение конфига блока series для hightcharts
     * @return array
     */
    public function getSeries() : array;


    /**
     * Получение конфига блока drilldown для hightcharts
     * @return array
     */
    public function getDrilldown() : array;


    /**
     * Получение id графика
     * @return string
     */
    public function getChartId() : string;


    /**
     * Получение заголовка графика
     * @return string
     */
    public function getChartTitle() : string;


    /**
     * Получение состояния индикатора. Отображается над графиком.
     * @return string
     */
    public function getIndicatorLabel() : string;


    /**
     * Получение конфига блока tooltip для hightcharts
     * @return array
     */
    public function getTooltip() : array;

    /**
     * Получение конфига блока xExis для hightcharts
     * @return array
     */
    public function getAxisX() : array;


    /**
     * Получение конфига блока yExis для hightcharts
     * @return array
     */
    public function getAxisY() : array;
}