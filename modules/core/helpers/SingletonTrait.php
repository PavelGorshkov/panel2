<?php
namespace app\modules\core\helpers;

/**
 * Trait SingletonTrait
 * @package app\modules\core\helpers
 */
trait SingletonTrait
{
    /**
     * @var mixed
     */
    private $_data = null;

    /**
     * @var SingletonTrait
     */
    private static $_instance = null;


    /**
     * @inheritdoc
     */
    private function __clone()
    {
    }


    /**
     * SingletonTrait constructor.
     */
    private function __construct()
    {
    }


    /**
     * @inheritdoc
     */
    public function initData()
    {
    }


    /**
     * @return array
     */
    public function getData()
    {

        return ($this->_data !== null) ? $this->_data : [];
    }


    /**
     * @return SingletonTrait
     */
    public static function model()
    {
        if (self::$_instance === null) {

            self::$_instance = new self;

            self::$_instance->initData();
        }

        return self::$_instance;
    }
}