<?php

namespace app\modules\finance\interfaces;

/**
 * Interface FinanceObserverInterface
 * @package app\modules\finance\interfaces
 */
interface FinanceObserverInterface
{
    /**
     * Получение данных для отображения
     * @param string $start
     * @param string $finish
     * @param bool $isPreview
     */
    public function createObserverData($start, $finish, $isPreview = false): void;


    /**
     * Получение данных для таблицы под графиком
     * @return array
     */
    public function getTableData(): array;


    /**
     * получение данных для pivot
     * @return array
     */
    public function getPivotData(): array;


    /**
     * Получение актуальных годов для модели относительно текущего
     * @param $year
     * @return array возвращает максимальный, минимальный и текущий
     */
    public function getActualYears($year): array;


    /**
     * Получение заголовка модели для вьюхи
     * @return string
     */
    public function getTitle(): string;


    /**
     * Получение начальной даты отсчета относительно данных выборки
     * @return string
     */
    public function getActualStart(): string;


    /**
     * Получение последней даты отсчета относительно данных выборки
     * @return string
     */
    public function getActualFinish(): string;
}