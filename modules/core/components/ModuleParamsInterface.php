<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 07.12.2017
 * Time: 9:04
 */

namespace app\modules\core\components;


interface ModuleParamsInterface {

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