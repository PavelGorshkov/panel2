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
class ModulePriority
{
    use SingletonTrait;

    protected $modified = false;

    /**
     * @param $module
     * @return array
     */
    public function __get($module)
    {

        return isset($this->_data[$module]) ? $this->_data[$module] : null;
    }


    /**
     * @param string $module
     * @param int $value
     */
    public function __set($module, $value)
    {

        if (empty($value)) return;

        if (!isset($this->_data[$module])) $this->_data[$module] = $value;

        $this->setModified();
    }


    /**
     * @param array $data
     * @param bool $save
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function setData(array $data, $save = false)
    {

        $this->_data = $data;
        $this->setModified();

        if ($save) return $this->saveData();

        return false;
    }


    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function saveData()
    {
        if (!$this->modified) return false;

        $this->_transform();
        ARModulePriority::saveData($this->_data);

        cache()->flush();

        return true;
    }


    /**
     * Сортировка приоритетов
     */
    private function _transform()
    {

        $sort = $this->_data;

        asort($sort);

        $data = [];
        $index = 1;
        foreach ($sort as $module => $temp) {

            $data[$module] = 10 * $index;
            $index++;
        }

        $this->_data = $data;
    }

    /**
     * @inheritdoc
     */
    public function initData()
    {
        $this->_data = ARModulePriority::findAllData();
    }


    /**
     * Удаление модулей из файла приоритетов
     *
     * @param string $module
     * @param bool $execute
     * @throws \yii\base\InvalidConfigException
     */
    public function unsetModule($module, $execute = false)
    {

        if (isset($this->_data[$module])) {

            unset($this->_data[$module]);
            $this->setModified();
        }

        if ($execute) $this->saveData();
    }


    /**
     * Изменение модификации данных
     */
    public function setModified()
    {
        $this->modified = true;
        $this->_transform();
    }


    /**
     * @param string $module
     * @param int $defaultValue
     * @return int
     */
    public function getPriority($module, $defaultValue)
    {
        if (!isset($this->_data[$module])) {

            $this->_data[$module] = $defaultValue;

            $this->setModified();
        }

        return $this->_data[$module];
    }
}