<?php

namespace app\modules\core\components;

use app\modules\core\helpers\File;
use app\modules\core\helpers\ModulePriority;
use app\modules\core\helpers\ModuleSettings;
use FilesystemIterator;
use iiifx\cache\dependency\FolderDependency;
use Yii;
use yii\base\Component;
use yii\caching\ChainedDependency;
use yii\caching\FileDependency;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;

/**
 * Компонент по управлению модулями системы
 *
 * Class ModuleManager
 * @package app\modules\core\components
 */
class ModuleManager extends Component
{

    private $_all_modules = null;


    private $_disabled_modules = null;


    private $_enabled_modules = null;


    /**
     * Определение всех модулей системы зависимых от приложения
     */
    private function _initAllModules()
    {
        $modules = cache()->get('all_modules');

        if ($modules === false) {

            $modules = $this->_scanModules();

            $chain = new ChainedDependency();

            $chain->dependencies = [

                new FileDependency(['fileName' => Yii::getAlias('@app/config/web.php')]),
                new FileDependency(['fileName' => Yii::getAlias('@app/config/console.php')]),
                new FolderDependency(['folder' => Yii::getAlias('@app/config/modules')]),
                new FolderDependency(['folder' => Yii::getAlias('@app/modules')]),
            ];

            cache()->set('all_modules', $modules, 3600, $chain);


        }

        $this->_all_modules = $modules;
    }


    /**
     * Определение неактивных (неустановленных) модулей в системе
     */
    private function _initDisabledModules()
    {

        $modules = cache()->get('disabled_modules');

        if ($modules === false) {

            $modules = $this->getAllModules();
            $enabled_modules = $this->getEnabledModules();

            foreach ($modules as $module => $moduleData) {

                if (isset($enabled_modules[$module])) {

                    unset($modules[$module]);
                }
            }

            $chain = new ChainedDependency();

            $chain->dependencies = [
                new TagDependency(['tags' => ['all_modules', 'enabled_modules']])
            ];

            cache()->set('disabled_modules', $modules, 3600, $chain);
        }

        $this->_disabled_modules = $modules;
    }


    /**
     * Определение установленных модулей, зависимых от приложения
     */
    private function _initEnabledModules()
    {
        $modules = cache()->get('enabled_modules');

        if ($modules === false) {

            $modules = $this->_scanEnabledModules();

            $chain = new ChainedDependency();

            $chain->dependencies = [
                new TagDependency(['tags' => ['all_modules']]),
                new FolderDependency(['folder' => Yii::getAlias('@app/config/modules')]),
            ];

            app()->cache->set('enabled_modules', $modules, 3600, $chain);
        }

        $this->_enabled_modules = $modules;
    }


    /**
     * Сканирование установленных модулей системы
     *
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    private function _scanEnabledModules()
    {
        $modules = [];
        $allModules = $this->getAllModules();

        if (count(app()->getModules())) {

            $counter = 1;
            $index = 1000;

            foreach (app()->getModules() as $key => $value) {

                if (!isset($allModules[$key])) continue;

                $class = new \ReflectionClass(is_array($value) ? $value['class'] : $value);

                if (!$class->implementsInterface('\app\modules\core\interfaces\ModuleParamsInterface')) continue;

                /**@var Module $module */
                $module = app()->getModule($key);

                $data = ArrayHelper::merge($allModules[$key],
                    [
                        'priority' => $allModules[$key]['is_system'] ? -100 + ($counter++) : ModulePriority::model()->getPriority($module->id, $index++),
                        'paramsCounter' => count($module->getParamLabels()),
                    ]);

                $modules[$key] = $data;
            }

            ModulePriority::model()->saveData();

            $modules = $this->_sortingModules($modules);
        }

        return $modules;
    }


    /**
     * Сканирование "@app/modules" на наличие модулей системы
     *
     * @return array
     */
    private function _scanModules()
    {

        $modules = [];
        $dependentModules = [];

        $modulesPath = Yii::getAlias('@app/modules');

        /* @var \SplFileInfo $item */
        foreach (new FilesystemIterator(Yii::getAlias('@app/modules')) as $item) {

            $moduleName = $item->getBasename();

            if (!is_dir($modulesPath . DIRECTORY_SEPARATOR . $moduleName)) continue;

            $classObject = 'Module';

            if (!file_exists($item->getRealPath() . DIRECTORY_SEPARATOR . $classObject . '.php'))
                continue;

            $isInstallConfig = file_exists(implode(DIRECTORY_SEPARATOR, [
                $item->getRealPath(),
                'install',
                'config.php',
            ]));

            $classObject = '\\app\\modules\\' . $moduleName . '\\' . $classObject;

            $reflection = new \ReflectionClass($classObject);

            /* @var \app\modules\core\interfaces\ModuleSettingsInterface $classObject */
            if (!$reflection->implementsInterface('\app\modules\core\interfaces\ModuleSettingsInterface')) continue;

            foreach ($classObject::dependsOnModules() as $d_module) {

                $dependentModules[$d_module][] = $moduleName;
            }

            $modules[$moduleName] = [
                'title' => $classObject::Title(),
                'is_system' => !$isInstallConfig,
                'dependsOn' => $classObject::dependsOnModules(),//зависит от модулей
                'dependent' => [], //зависимые модули
            ];
        }

        foreach ($modules as $module => $temp) {

            if (isset($dependentModules[$module])) {

                $modules[$module]['dependent'] = $dependentModules[$module];
            }
        }

        return $modules;
    }


    /**
     * Сортировка модулей по параметру
     *
     * @param array $modules
     *
     * @return array
     */
    private function _sortingModules($modules)
    {
        $sort = [];

        foreach ($modules as $module => $data) {

            $sort[$module] = $data['priority'];
        }

        asort($sort);

        $data = [];
        foreach ($sort as $module => $temp) {

            $data[$module] = $modules[$module];
        }

        return $data;
    }


    /**
     * Проверка доступа к ресурсу модуля
     *
     * @param string $module
     * @param []|string $access
     *
     * @return bool
     */
    public function can($module, $access)
    {
        $list = $this->getEnabledModules();

        if (!isset($list[$module])) return false;

        return user()->can($access);
    }


    /**
     * Получение массива всех модулей приложения, наследованных
     * от базового модуля \app\modules\core\components\Module
     */
    public function getAllModules()
    {
        if ($this->_all_modules === null) $this->_initAllModules();

        return $this->_all_modules;
    }


    /**
     * Получение массива отключенных модулей приложения
     *
     * @return array|null
     */
    public function getDisabledModules()
    {
        if ($this->_disabled_modules === null) $this->_initDisabledModules();

        return $this->_disabled_modules;
    }


    /**
     * Получение массива подключенных модулей приложения
     *
     * @return array|null
     */
    public function getEnabledModules()
    {
        if ($this->_enabled_modules === null) $this->_initEnabledModules();

        return $this->_enabled_modules;
    }


    /**
     * Получение списка всех модулей приложения
     *
     * @return array
     */
    public function getListAllModules()
    {
        return array_keys($this->getAllModules());
    }


    /**
     * Получение списка всех активных модулей приложения
     *
     * @return array
     */
    public function getListEnabledModules()
    {
        return array_keys($this->getEnabledModules());
    }


    /**
     * Возвращает меню из подключенных модулей
     *
     * @param array $types
     * @return array
     */
    public function getMenu(array $types)
    {

        $menu = [];;

        foreach ($types as $type) {

            $method = 'getMenu' . ucfirst($type);
            $menu[$type] = [];

            foreach ($this->getListEnabledModules() as $module) {

                $moduleApp = app()->getModule($module);

                if (method_exists($moduleApp, $method)) {

                    $local_menu = $moduleApp->$method();
                    if (count($local_menu)) $menu[$type] = ArrayHelper::merge($menu[$type], $local_menu);
                }
            }
        }

        return $menu;
    }


    /**
     * @param string $module
     * @return null|string
     */
    public function getTitle($module)
    {

        $modules = $this->getEnabledModules();

        if (!isset($modules[$module])) return null;

        return $modules[$module]['title'];
    }


    /**
     * @param string $module
     * @return bool
     */
    protected function installConfig($module)
    {
        return File::cpFile(
            Yii::getAlias('@app/modules/'.$module.'/install').'/config.php',
            Yii::getAlias('@app/config/modules').'/'.$module.'.php'
        );
    }


    /**
     * Существует ли модуль
     *
     * @param string $module
     *
     * @return bool
     */
    public function isExistsModule($module)
    {

        return in_array($module, $this->getListAllModules());
    }


    /**
     * Подключен ли модуль
     *
     * @param string $module
     *
     * @return bool
     */
    public function isInstallModule($module)
    {

        return in_array($module, $this->getListEnabledModules());
    }


    /**
     * @param string $module
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    protected function removeModule($module)
    {
        ModulePriority::model()->unsetModule($module, true);
        ModuleSettings::model()->delete($module);

        return @unlink(Yii::getAlias('@app/config/modules').'/'.$module.'.php');


    }


    /**
     * @param string $module
     * @return bool
     */
    public function onModule($module)
    {
        $data = $this->getDisabledModules();

        if (isset($data[$module])) {

            $data = $data[$module];

            if (count($data['dependsOn'])) {

                foreach ($data['dependsOn'] as $dep_module) $this->onModule($dep_module);
            }

            $this->installConfig($module);

            return true;
        }

        return false;
    }


    /**
     * @param string $module
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function offModule($module) {

        $data = $this->getEnabledModules();

        if (isset($data[$module])) {

            $data = $data[$module];

            if (count($data['dependent'])) {

                foreach ($data['dependent'] as $dep_module) $this->offModule($dep_module);
            }

            $this->removeModule($module);

            return true;
        }

        return false;
    }
}