<?php
namespace app\modules\core\interfaces;

/**
 * Интерфейс для работы модуля с параметрами модуля, хранимыми в БД
 *
 * Interface ModuleParamsInterface
 * @package app\modules\core\components
 */
interface ModuleParamsInterface
{

    /**
     * Возвращает список параметров, хранимых в системе
     *
     * @return array
     */
    public function getParamGroups();


    /**
     * Возвращает список названий параметров
     *
     * @return array
     */
    public function getParamLabels();


    /**
     * Возвращает список функций для select
     *
     * @return array
     */
    public function getParamsDropdown();
}