<?php
namespace app\modules\user\helpers;

use app\modules\core\helpers\GetterSingletonTrait;
use app\modules\core\models\Settings;

/**
 * Класс helper для работы с пользовательскими данными
 *
 * Class UserSettings
 * @package app\modules\core\helpers
 */
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