<?php
namespace app\modules\core\components;

use app\modules\core\helpers\ConfigCacheTrait;
use app\modules\core\helpers\File;
use GlobIterator;
use \SplFileInfo;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * Класс конфигуратор сборки конфига всего приложения
 *
 * Class ConfigManager
 * @package app\modules\core\components
 */
class ConfigManager
{
    use ConfigCacheTrait;

    const ENV_WEB = 'web';

    const ENV_CONSOLE = 'console';

    private $_base = [];

    private $_config = [];

    public $configCategories = [
        'rules',
        'components',
        'bootstrap',
        'module',
        'modules',
    ];

    /**
     * @var string
     */
    private $env = self::ENV_WEB;


    /**
     * ConfigManager constructor.
     * @param null $type
     */
    public function __construct($type = null)
    {
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
     * Проверка на актульность конфига модуля
     *
     * @param \SplFileInfo $moduleConfigFile
     *
     * @return bool
     */
    protected function checkUpdateModuleConfig(\SplFileInfo $moduleConfigFile)
    {
        $moduleFile = Yii::getAlias(
            sprintf(
                '@app/modules/%s/install/config.php',
                $moduleConfigFile->getBasename('.php')
            ));

        if (!file_exists($moduleFile)) {

            unlink(Yii::getAlias('@app/config/modules/' . $moduleConfigFile->getBasename()));
            return true;
        }

        return File::cpFileOriginal($moduleFile, $moduleConfigFile->getRealPath());
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
            ? File::includePhpFile($this->getConfigEnv())
            : $base;

        $config = $this->getSettings();

        unset($config['rules']);

        return $config;
    }


    /**
     * @return string path
     */
    protected function getConfigEnv()
    {
        return Yii::getAlias('@app/config/') . $this->env . '.php';
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
            if (!is_dir(Yii::getAlias(sprintf('@app/modules/%s', $item->getBasename('.php'))))) {

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
     * @return mixed
     * @throws Exception
     */
    protected function getSettings()
    {
        $settings = $this->isUpdateConfigModules()
            ? null
            : $this->getCacheSettings();

        if ($settings === null) {

            $settings = $this->prepareSettings();

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

        /** @var SplFileInfo $item */
        foreach (new GlobIterator(Yii::getAlias('@app/config/modules/*.php')) as $item) {

            if (
                is_dir(
                    Yii::getAlias(
                        sprintf('@app/modules/%s', $item->getBasename('.php')))
                ) === false) {
                continue;
            }

            $this->checkUpdateModuleConfig($item);

            $moduleConfig = File::includePhpFile($item->getRealPath());

            foreach ($this->configCategories as $category) {

                switch ($category) {

                    case 'modules':
                        if (!empty($moduleConfig['modules'])) {

                            $settings['modules'] = ArrayHelper::merge(
                                isset($settings['modules']) ? $settings['modules'] : [],
                                 $moduleConfig['modules']
                            );
                        }
                    break;

                    case 'module':

                        if (!empty($moduleConfig['module'])) {

                            $settings['modules'] = ArrayHelper::merge(
                                isset($settings['modules']) ? $settings['modules'] : [],
                                [$item->getBasename('.php') => $moduleConfig['module']]
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


    /**
     * @param array $settings
     * @return array
     */
    protected function mergeSettings($settings = [])
    {
        $this->_config = ArrayHelper::merge(
            $this->_base,
            [
                'bootstrap' => ArrayHelper::merge(
                    isset($this->_config['bootstrap']) ? $this->_config['bootstrap'] : [],
                    isset($settings['bootstrap']) ? $settings['bootstrap'] : []
                ),

                'aliases' => ArrayHelper::merge(
                    isset($this->_config['aliases']) ? $this->_config['aliases'] : [],
                    isset($settings['aliases']) ? $settings['aliases'] : []
                ),

                // Модули:
                'modules' => ArrayHelper::merge(
                    isset($this->_config['modules']) ? $this->_config['modules'] : [],
                    isset($settings['modules']) ? $settings['modules'] : []
                ),

                'components' => ArrayHelper::merge(
                    isset($this->_config['components']) ? $this->_config['components'] : [],
                    isset($settings['components']) ? $settings['components'] : []
                ),

                'rules' => isset($settings['rules']) ? $settings['rules'] : [],
            ]
        );

        if (!array_key_exists('rules', $settings)) {
            $settings['rules'] = [];
        }

        if (!array_key_exists('cache', $settings)) {
            $settings['cache'] = [];
        }

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


    /**
     * @param array $settings
     * @return array
     */
    protected function mergeRules($settings = [])
    {

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