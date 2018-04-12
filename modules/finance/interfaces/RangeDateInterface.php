<?php

namespace app\modules\finance\interfaces;

/**
 * Interface RangeDateInterface
 * @package app\modules\finance\interfaces
 */
interface RangeDateInterface
{
    /**
     * Получение актуальных годов для модели относительно текущего
     * @param $year
     * @return array возвращает максимальный, минимальный и текущий
     */
    public function getActualYears($year);
}