<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 09.12.2017
 * Time: 10:59
 */

namespace app\modules\core\helpers;


trait SingletonTrait {

    /**
     * @var mixed
     */
    private $_data = null;

    /**
     * @var SingletonTrait
     */
    private static $_instance = null;

    private function __clone(){}
    private function __construct(){}


    public function initData(){}

    /**
     * @return array
     */
    public function getData() {

        return ($this->_data!==null)?$this->_data:[];
    }


    /**
     *
     * @return SingletonTrait
     */
    public static function model() {

        if (self::$_instance === null) {

            self::$_instance = new self;

            self::$_instance->initData();
        }

        return self::$_instance;
    }

}