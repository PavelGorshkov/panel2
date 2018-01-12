<?php
namespace app\modules\user\helpers;

use app\modules\core\helpers\GetterSingletonTrait;
use app\modules\core\models\Settings;

/**
 * Класс helper для работы с пользовательскими данными
 *
 * Class UserSettings
 * @package app\modules\core\helpers
 *
 * @method static UserSettings model
 * @property string $skinTemplate
 * @property string $sideBar
 */
class UserSettings {

    use GetterSingletonTrait;

    /**
     * Инициализация данных
     */
    public function initData() {

        $this->_data = Settings::findAllUserData();
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value) {

        $this->_data[$name] = $value;

        Settings::saveUserData($name, $value);
    }

}