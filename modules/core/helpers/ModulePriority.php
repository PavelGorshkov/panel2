<?php
namespace app\modules\core\helpers;

use app\modules\core\models\ModulePriority as ARModulePriority;


/**
 * Класс хранящий приоритеты модулей
 *
 * Class ModulePriority
 * @package app\modules\core\helpers
 *
 * @method static ModulePriority model()
 */
class ModulePriority {

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
     * @param int $value
     * @throws \yii\base\InvalidConfigException
     */
    public function __set($module, $value) {

        if (empty($value)) return;

        if (!isset($this->_data[$module])) $this->_data[$module] = $value;

        $this->_transform();

        ARModulePriority::saveData($this->_data);
    }


    /**
     * @param array $data
     * @throws \yii\base\InvalidConfigException
     */
    public function saveData(array $data) {

        $this->_data = $data;
        $this->_transform();

        ARModulePriority::saveData($this->_data);
    }


    /**
     * Сортировка приоритетов
     */
    private function _transform() {

        $sort = $this->_data;

        asort($sort);

        $data = [];
        $index = 1;
        foreach ($sort as $module => $temp) {

            $data[$module] = 10*$index;
            $index++;
        }

        $this->_data = $data;
    }

    /**
     * @inheritdoc
     */
    public function initData() {

        $this->_data = ARModulePriority::findAllData();
    }


    /**
     * Удаление модулей из файла приоритетов
     *
     * @param $data
     */
    public function unsetData($data) {

        foreach ($data as $module => $temp) {

            if (isset($this->_data[$module])) unset($this->_data[$module]);
        }
    }
}