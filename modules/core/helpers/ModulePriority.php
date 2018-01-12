<?php
namespace app\modules\core\helpers;

use app\modules\core\components\ConfigManager;
use Yii;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

/**
 * Класс хранящий приоритеты модулей
 *
 * Class ModulePriority
 * @package app\modules\core\helpers
 *
 * @method static ModulePriority model()
 */
class ModulePriority {

    use GetterSingletonTrait;

    private $file = 'module_priority';


    /**
     * @return array
     * @throws ServerErrorHttpException
     */
    private function _createPriorityModuleFile() {

        $configPath = Yii::getAlias('@app/config/modules');
        $modulePath = Yii::getAlias('@app/modules');

        $data = [];

        $counter = 0;

        /* @var \SplFileInfo $item */
        foreach (new \GlobIterator($configPath.'/*.php') as $item) {

            $module = $item->getBasename('.php');

            if (!is_dir($modulePath.DIRECTORY_SEPARATOR.$module)) continue;

            $data[$module] = $this->_setPriority($counter);
            $counter++;
        }

        $this->_saveFile($data);

        return $data;
    }


    /**
     * @param string $name
     * @return mixed|null
     */
    public function __get($name) {

        if(isset($this->_data[$name]))

            return $this->_data[$name];
        else

            return null;
    }


    /**
     * Шаблон json файла списка приоритетов модулей
     *
     * @param array $data
     *
     * @return string
     */
    private function _getFileTemplateJSON(array $data) {

        return json_encode($data);
    }


    /**
     * Проверка существования файла списка приоритетов модулей
     *
     * @return bool
     */
    private function _issetFile() {

        return file_exists($this->getFile());
    }


    /**
     * Чтение данных из файла списка приоритетов модулей
     *
     * @return array|null
     */
    private function _loadFile() {

        if (!$this->_issetFile()) return null;

        $data = @json_decode(file_get_contents($this->getFile()), 1);

        if (is_array($data) === false) {$data = null;}

        return $data;
    }


    /**
     * Сохранение данных в файл списка приоритетов модулей
     *
     * @param array $data
     * @return bool
     * @throws ServerErrorHttpException
     */
    private function _saveFile(array $data) {

        if (!file_put_contents($this->getFile(), $this->_getFileTemplateJSON($data))) {

            throw new ServerErrorHttpException('Ошибка записи кеша в файл '.$this->getFile().' в классе "'.__CLASS__.'"');
        }

        (new ConfigManager())->flushCache();

        return true;
    }


    /**
     * Формула вычисления приоритета
     *
     * @param int $count
     * @return int
     */
    private function _setPriority($count) {

        return ($count+1)*10;
    }


    /**
     * Сортировка модулей по приоритету
     *
     * @return array
     */
    private function _sortingData() {

        $data = $this->_data;

        asort($data);
        reset($data);

        $counter = 0;
        foreach ($data as $m => $temp) $data[$m] = $this->_setPriority($counter++);

        return $data;
    }


    /**
     * Обновление файла данных приоритета модулей
     *
     * @return bool
     *
     * @throws HttpException
     */
    private function _updatePriorityModuleFile() {

        $this->_data = $this->_sortingData();

        return $this->_saveFile($this->_data);
    }


    /**
     * Путь до файла списка приоритетов модулей
     *
     * @return string
     */
    public function getFile() {

        return Yii::getAlias('@app/runtime/'.$this->file.'.json');
    }


    /**
     * @throws ServerErrorHttpException
     */
    public function initData() {

        if (($data = $this->_loadFile()) === null) {

            $data = $this->_createPriorityModuleFile();
        }

        $this->_data = $data;
    }


    /**
     * Изменение приоритета модулей
     *
     * @param array $data
     *
     * @return bool
     * @throws HttpException
     */
    public function setData(array $data) {

        foreach ($data as $module => $priority) {

            if (isset($this->_data[$module])) {

                $this->_data[$module] = $priority>10?$priority:$this->_data[$module];
            } else {

                $this->_data[$module] = $this->_setPriority(count($this->_data));
            }
        }

        return $this->_updatePriorityModuleFile();
    }


    /**
     * Удаление модулей из файла приоритетов
     *
     * @param $data
     * @throws HttpException
     */
    public function unsetData($data) {

        foreach ($data as $module => $temp) {

            if (isset($this->_data[$module])) unset($this->_data[$module]);
        }

        $this->_updatePriorityModuleFile();
    }
}