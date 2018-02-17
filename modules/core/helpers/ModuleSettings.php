<?php

namespace app\modules\core\helpers;

use app\modules\core\models\ModuleSettings as ARModuleSettings;


/**
 * Класс helper для работы с настройками модулей из БД
 *
 * Class ModuleSettings
 * @package app\modules\core\helpers
 *
 * @method static ModuleSettings model()
 */
class ModuleSettings
{

    use SingletonTrait;

    /**
     * @param $module
     * @return array
     */
    public function __get($module)
    {

        return isset($this->_data[$module]) ? $this->_data[$module] : [];

    }


    /**
     * @param string $module
     * @param array $data
     * @throws \yii\base\InvalidConfigException
     */
    public function __set($module, $data)
    {
        if (!is_array($data) || !count($data)) return;

        if (!isset($this->_data[$module])) $this->_data[$module] = [];

        ARModuleSettings::saveData($module, $data);
    }


    /**
     * @inheritdoc
     */
    public function initData()
    {
        $this->_data = ARModuleSettings::findAllData();
    }


    /**
     * @param string $module
     */
    public function delete($module)
    {
        ARModuleSettings::deleteAll(['module' => $module]);
    }
}