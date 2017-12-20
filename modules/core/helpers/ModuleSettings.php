<?php
namespace app\modules\core\helpers;

use app\modules\core\models\Settings;

/**
 * Класс helper для работы с настройками модулей из БД
 *
 * Class ModuleSettings
 * @package app\modules\core\helpers
 */
class ModuleSettings {

    use SingletonTrait;

    public function __get($module) {

        return isset($this->_data[$module])?$this->_data[$module]:[];

    }


    public function __set($module, $data) {

        if (!is_array($data) || !count($data)) return;

        if(!isset($this->_data[$module])) $this->_data[$module] = [];

        Settings::saveModuleData($data);
    }


    public function initData() {

        $this->_data = Settings::findAllModuleData();
    }
}