<?php

namespace app\modules\user\helpers;

use app\modules\core\helpers\GetterSingletonTrait;
use app\modules\core\models\UserSettings as ARUserSettings;

/**
 * Класс helper для работы с пользовательскими данными
 *
 * Class UserSettings
 * @package app\modules\core\helpers
 *
 * @method static UserSettings model
 * @property string $skinTemplate
 * @property string $sideBar
 * @property string $startPage
 */
class UserSettings
{
    use GetterSingletonTrait;

    /**
     * Инициализация данных
     */
    public function initData()
    {
        $this->_data = ARUserSettings::findAllData();
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value)
    {
        $this->_data[$name] = $value;

        ARUserSettings::saveData($name, $value);
    }


    /**
     *
     */
    public function deleteAll()
    {
        ARUserSettings::deleteAllData();
    }
}