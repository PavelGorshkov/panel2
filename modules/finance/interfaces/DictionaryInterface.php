<?php

namespace app\modules\finance\interfaces;

/**
 * Interface DictionaryInterface
 * @package app\modules\finance\interfaces
 */
interface DictionaryInterface{

    /**
     * Получение списка всех значений справочника
     * @return array
     */
    public function getItems() : array;
}