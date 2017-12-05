<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 05.12.2017
 * Time: 16:32
 */

namespace app\components;


use app\helpers\File;
use GlobIterator;
use Yii;

class ConfigManager {

    const ENV_WEB = 'web';

    const ENV_CONSOLE = 'console';

    private $_base = [];

    public $configCategories = [
        'rules',
        'components',
        'bootstrap',
        'modules',
        'behaviors'
    ];

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


    public function initPath()
    {
        $this->modulePath = Yii::getAlias('@app/config/modules');
        $this->appModules = Yii::getAlias('@app/modules');
    }


    /**
     * @param array $base
     * @return array
     */
    public function merge(array $base = [])
    {
        $this->_base = empty($base)? require_once Yii::getAlias('@app/config/').$this->env.'.php' : $base;
        // Настройки путей:
        $this->initPath();

        return $this->getSettings();
    }


    /**
     * @return mixed
     */
    public function getSettings()
    {
        $settings = $this->getCacheSettings();

        if ($settings === null) {

            $settings = $this->prepareSettings();
        }
        printr($settings, 1);

        //return $this->mergeRules($settings);
    }


    /**
     * Возвращает закешированный конфиг
     *
     * @return array|null
     */
    public function getCacheSettings() {

        return $this->isCached()?$this->loadCache():null;
    }


    protected function isCached() {

        if ($this->isDebug()) return false;

        return $this->isUpdateConfigModules() ? false : file_exists($this->getCacheFile());
    }


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

            // Если нет такого модуля, нет необходимости в обработке:
            if (!is_dir( Yii::getAlias( sprintf('@app/modules/%s', $item->getBaseName('.php'))))) {

                continue;
            }
            printr($item, 1);

            if ($this->checkUpdateModuleConfig($item)) {

                $this->flushCache();

                $status = true;
            }
        }

        return $status;
    }


    protected function checkUpdateModuleConfig(\SplFileInfo $moduleConfigFile) {

        $moduleConfig = Yii::getAlias(sprintf('@app/config/modules/%s/install/config.php', $moduleConfigFile));

        if (!file_exists($moduleConfig)) {

            printr($moduleConfigFile, 1);
        }
    }


    protected function getCacheFile() {

        if (!File::checkPath($this->getCachePath())) {

            throw new \CException('Check rights path: '.$this->getCachePath());
        };

        return Yii::getAlias(sprintf("%s/%s.json", $this->getCachePath(), $this->env));
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
     * @throws CException
     */
    public function createCache(array $data) {

        // Если выключена опция кеширования настроек - не выполняем его:
        if ($this->isDebug()) return true;

        if (!@file_put_contents($this->getCacheFile(), $this->getFileTemplateJSON($data), LOCK_EX)) {
            throw new CHttpException('500', 'Ошибка записи кеша в файл '.$this->getCacheFile().' в классе "'.__CLASS__.'"');
        }

        return true;
    }


    /**
     * Загрузка из кэша
     *
     * @return array|null
     * @throws \CException
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


    protected function prepareSettings()
    {
        $settings = [];

        printr(Yii::getAlias('@app/config/modules/*.php'));
        foreach (new GlobIterator(Yii::getAlias('@app/config/modules/*.php')) as $item ) {

        };
    }
}