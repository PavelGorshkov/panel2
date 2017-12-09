<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 09.12.2017
 * Time: 11:10
 */

namespace app\modules\core\helpers;

use app\modules\core\models\Settings;

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