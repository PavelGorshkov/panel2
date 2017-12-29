<?php
namespace app\modules\core\components;

use app\modules\core\helpers\File;
use GlobIterator;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

/**
 * Класс конфигуратор сборки конфига всего приложения
 *
 * Class ConfigManager
 * @package app\modules\core\components
 */
class ConfigManager {

    const ENV_WEB = 'web';

    const ENV_CONSOLE = 'console';

    private $_base = [];

    private $_config = [];

    public $configCategories = [
        'rules',
        'components',
        'bootstrap',
        'module',
    ];

    /**
     * @var string
     */
    private $env = self::ENV_WEB;


    public function __construct($type = null) {

        if ($type !== null) {

            switch ($type) {

                case self::ENV_CONSOLE:
                case self::ENV_WEB:

                    $this->env = $type;
                    break;
            }
        }
    }


    /**
     * Объединяет конфиги приложения
     *
     * @param array $base
     * @return array
     * @throws Exception
     */
    public function merge(array $base = [])
    {
        $this->_base = empty($base)
            ?require_once $this->getConfigEnv()
            :$base;

        $config = $this->getSettings();

        unset($config['rules']);

        return $config;
    }


    /**
     * Возвращает закешированный конфиг
     *
     * @return array|null
     * @throws Exception
     * @throws HttpException
     */
    protected function getCacheSettings() {

        return $this->isCached()?$this->loadCache():null;
    }


    /**
     * @return string path
     */
    protected function getConfigEnv() {

        return Yii::getAlias('@app/config/').$this->env.'.php';
    }


    /**
     * @return bool
     * @throws HttpException
     */
    protected function isCached() {

        if ($this->isDebug()) return false;

        return $this->isUpdateConfigModules() ? false : file_exists($this->getCacheFile());
    }


    /**
     * путь хранения директории cache конфига
     *
     * @return bool|string
     */
    protected function getCachePath() {

        return Yii::getAlias('@app/runtime/config');
    }


    /**
     * Проверка на обновление конфигов модулей
     *
     * @return bool
     */
    protected function isUpdateConfigModules()
    {
        $status = false;

        foreach (new GlobIterator(Yii::getAlias('@app/config/modules/*.php')) as $item) {

            /* @var \SplFileInfo $item */
            // Если нет такого модуля, нет необходимости в обработке:
            if (!is_dir( Yii::getAlias( sprintf('@app/modules/%s', $item->getBaseName('.php'))))) {

                unlink($item->getPathname());
                continue;
            }

            if ($this->checkUpdateModuleConfig($item)) {

                $this->flushCache();
                $status = true;
            }
        }

        return $status;
    }


    /**
     * Проверка на актульность конфига модуля
     *
     * @param \SplFileInfo $moduleConfigFile
     *
     * @return bool
     */
    protected function checkUpdateModuleConfig(\SplFileInfo $moduleConfigFile) {

        $moduleFile = Yii::getAlias(sprintf('@app/modules/%s/install/config.php', $moduleConfigFile->getBaseName('.php')));

        if (!file_exists($moduleFile)) {

            unlink(Yii::getAlias('@app/config/modules/'.$moduleConfigFile->getBasename()));
            return true;
        }

        if (file_crc32($moduleFile) !== file_crc32($moduleConfigFile->getRealPath())) {

            File::cpFile($moduleFile, $moduleConfigFile->getRealPath());
            return true;
        }

        return false;
    }


    /**
     * @return string
     *
     * @throws HttpException
     */
    protected function getCacheFile() {

        if (!File::checkPath($this->getCachePath())) {

            throw new HttpException(500, 'Check rights path: '.$this->getCachePath());
        };

        return Yii::getAlias(sprintf("%s/%s.json", $this->getCachePath(), $this->env));
    }


    /**
     * Шаблон генерации json файла
     *
     * @param array $data
     * @return string
     */
    private function getFileTemplateJSON(array $data) {

        return json_encode($data);
    }


    /**
     * Проверка debug режима
     *
     * @return bool
     */
    protected function isDebug() {

        return (defined('\YII_DEBUG') && \YII_DEBUG === true)
            || (defined('YII_ENV') && YII_ENV === 'dev');
    }


    /**
     * @param array $data
     * @return bool
     *
     * @throws HttpException
     */
    public function createCache(array $data) {

        // Если выключена опция кеширования настроек - не выполняем его:
        if ($this->isDebug()) return true;

        if (!@file_put_contents($this->getCacheFile(), $this->getFileTemplateJSON($data), LOCK_EX)) {
            throw new ServerErrorHttpException('Ошибка записи кеша в файл '.$this->getCacheFile().' в классе "'.__CLASS__.'"');
        }

        return true;
    }


    /**
     * Загрузка из кэша
     *
     * @return array|null
     */
    protected function loadCache()
    {
        try {
            $data = @json_decode(file_get_contents($this->getCacheFile()), 1);

            if (is_array($data) === false) {$data = null;}

        } catch (Exception $e) {

            $data = null;
        }

        return $data;
    }


    /**
     * Очистка cache конфига
     */
    public function flushCache() {

        if (is_dir($this->getCachePath())) {

            File::rmDir($this->getCachePath().DIRECTORY_SEPARATOR.'*');
        }
    }


    /**
     * @return mixed
     * @throws Exception
     */
    protected function getSettings()
    {
        $settings = $this->getCacheSettings();

        if ($settings === null) {

            $settings = $this->prepareSettings();

            // Создание кеша настроек:
            if (($error = $this->createCache($settings)) !== true) {

                throw new Exception('Не возможно создать кеш файла конфигурации');
            }
        }


        $settings = $this->mergeSettings($settings);

        $settings = $this->mergeRules($settings);

        return $settings;
    }


    /**
     * @return array
     */
    protected function prepareSettings()
    {
        $settings = [];

        foreach (new GlobIterator(Yii::getAlias('@app/config/modules/*.php')) as $item ) {

            if (is_dir(Yii::getAlias(sprintf('@app/modules/%s', $item->getBaseName('.php')))) == false) {
                continue;
            }

            $this->checkUpdateModuleConfig($item);

            $moduleConfig = require $item->getRealPath();

            foreach ($this->configCategories as $category) {

                switch ($category) {

                    case 'module':

                        if (!empty($moduleConfig['module'])) {

                            $settings['modules'] = ArrayHelper::merge(
                                isset($settings['modules']) ? $settings['modules'] : [],
                                [$item->getBaseName('.php') => $moduleConfig['module']]
                            );
                        }

                        break;

                    default:

                        if (!empty($moduleConfig[$category])) {

                            $settings[$category] = ArrayHelper::merge(
                                isset($settings[$category]) ? $settings[$category] : [],
                                $moduleConfig[$category]
                            );
                        }

                        break;
                }
            }
        }

        return $settings;
    }


    protected function mergeSettings($settings = []) {

        $this->_config = ArrayHelper::merge(
            $this->_base,
            [
                'bootstrap' => ArrayHelper::merge(
                    isset($this->_config['bootstrap'])? $this->_config['bootstrap']: [],
                    isset($settings['bootstrap'])? $settings['bootstrap']: []
                ),

                'aliases'=> ArrayHelper::merge(
                    isset($this->_config['aliases'])? $this->_config['aliases']: [],
                    isset($settings['aliases'])? $settings['aliases']: []
                ),

                // Модули:
                'modules' => ArrayHelper::merge(
                    isset($this->_config['modules'])? $this->_config['modules']: [],
                    isset($settings['modules'])? $settings['modules']: []
                ),

                'components' => ArrayHelper::merge(
                    isset($this->_config['components'])? $this->_config['components']: [],
                    isset($settings['components'])? $settings['components']: []
                ),

                'rules' => isset($settings['rules'])? $settings['rules']: [],
            ]
        );

        if (!array_key_exists('rules', $settings)) {$settings['rules'] = [];}

        if (!array_key_exists('cache', $settings)) {$settings['cache'] = [];}

        if (isset($this->_config['components']['urlManager']['rules'])) {
            // Фикс для настроек маршрутизации:
            $this->_config['components']['urlManager']['rules'] = ArrayHelper::merge(
                $this->_config['components']['urlManager']['rules'],
                $settings['rules']
            );
        }

        if (isset($this->_config['components']['cache'])) {
            // Слитие настроек для компонента кеширования:
            $this->_config['components']['cache'] = ArrayHelper::merge(
                $this->_config['components']['cache'],
                $settings['cache']
            );
        }

        return $this->_config;
    }


    protected function mergeRules($settings = []) {

        if (isset($settings['components']['urlManager'])) {

            $rules = $settings['rules'];

            unset($settings['rules']);

            foreach ($settings['components']['urlManager']['rules'] as $key => $value) {

                $search = array_search($value, $rules);

                if (!empty($search) || isset($rules[$key]) || false === $value) {
                    unset($settings['components']['urlManager']['rules'][$key]);
                }
            }

            $settings['components']['urlManager']['rules'] = ArrayHelper::merge(
                $rules,
                $settings['components']['urlManager']['rules']
            );
        }

        return $settings;
    }


}