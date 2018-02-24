<?php

namespace app\modules\core\helpers;

/**
 * Синглтон с магическим методом __set и  __isset
 *
 * Class GetterSingletonTrait
 * @package app\modules\core\helpers
 */
trait GetterSingletonTrait
{
    use SingletonTrait;

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        if (isset($this->_data[$name]))

            return $this->_data[$name];
        else
            return null;
    }


    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->_data[$name]);
    }


    /**
     * Получение массива данных класса
     *
     * @return array
     */
    public function getList()
    {
        return $this->_data;
    }
}