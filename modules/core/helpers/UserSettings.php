<?php
namespace app\modules\core\helpers;

use app\modules\core\models\Settings;

class UserSettings {

    use GetterSingletonTrait;

    public function initData() {

        $this->_data = Settings::findAllUserData();
    }


    public function __set($name, $value) {

        $this->_data[$name] = $value;

        Settings::saveUserData($name, $value);
    }

}