<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 09.12.2017
 * Time: 11:01
 */

namespace app\modules\core\helpers;


trait GetterSingletonTrait {

    use SingletonTrait;

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function __get($name) {

        if(isset($this->_data[$name]))

            return $this->_data[$name];
        else
            return null;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name) {

        return isset($this->_data[$name]);

    }


    /**
     * @return array
     */
    public function getList() {

        return $this->_data;
    }

}