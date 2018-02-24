<?php

namespace app\modules\core\components;

use Yii;
use yii\base\Component;
use yii\base\UnknownPropertyException;
use yii\helpers\ArrayHelper;

/**
 * Class MenuManager
 * @package app\modules\core\components
 *
 * @property array admin - меню админа
 * @property array main - Основное меню
 * @property array redactor - Меню редактора
 *
 */
class MenuManager extends Component
{
    const MENU_DATABASE = 'database';

    const MENU_MODULE = 'module';

    const MENU_STATIC = 'file';

    const TYPE_ADMIN = 'admin';

    const TYPE_MAIN = 'main';

    const TYPE_REDACTOR = 'redactor';

    /**
     * @var array|null
     */
    protected $_menu = null;

    /**
     * @var array|null
     */
    public $type = [
        self::TYPE_ADMIN => self::MENU_MODULE,
        self::TYPE_MAIN => self::MENU_MODULE,
        self::TYPE_REDACTOR => self::MENU_MODULE,
    ];

    protected $internalType = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->type !== null) {

            foreach ($this->type as $key => $type) {

                $this->internalType[$type][] = $key;
            }
        }
    }


    /**
     * @return array|null
     * @throws \yii\base\InvalidConfigException
     */
    public function getData()
    {
        if ($this->_menu === null) $this->setMenu();

        return $this->_menu;
    }


    /**
     * Установка меню
     * @throws \yii\base\InvalidConfigException
     */
    public function setMenu()
    {
        $this->_menu = app()->cache->get('cacheMenu_' . user()->id);

        if ($this->_menu === false) {

            $this->prepareMenu();
        }
    }

    /**
     * @param $type
     * @return string
     */
    public function getMenuConfigFile($type)
    {
        return Yii::getAlias('@app/config/menu/' . $type . '.php');
    }


    /**
     * Очистка кеша
     */
    public function clearCache()
    {
        app()->cache->flush();
    }


    /**
     * Сборка меню
     * @throws \yii\base\InvalidConfigException
     */
    protected function prepareMenu()
    {
        $menu = [];

        foreach ($this->internalType as $type => $types) {

            switch ($type) {

                case self::MENU_STATIC:

                    foreach ($types as $key) {

                        $file = $this->getMenuConfigFile($key);
                        if (file_exists($file)) /** @noinspection PhpIncludeInspection */
                            $menu[$key] = include $file;
                    }

                    break;

                case self::MENU_MODULE:

                    $menu = ArrayHelper::merge($menu, app()->moduleManager->getMenu($types));

                    break;

                case self::MENU_DATABASE:

                    if (!app()->hasModule('menu')) break;

                    else {

                        foreach ($types as $key) {
                            $menu[$key] = $this->getDBMenu($key);
                        }
                    }

                    break;
            }
        }

        $this->_menu = $menu;

        app()->cache->set('cacheMenu_' . user()->id, $menu, 0);
    }


    /**
     * @param string $name
     * @return mixed|null
     * @throws UnknownPropertyException
     * @throws \yii\base\InvalidConfigException
     */
    public function __get($name)
    {

        if ($this->_menu === null) $this->setMenu();

        $name = strtolower($name);

        if (isset($this->_menu[$name])) {

            return $this->getSubMenu($name);

        } else return parent::__get($name);
    }


    /**
     * @param string $name
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function __isset($name)
    {
        if ($this->_menu === null) $this->setMenu();

        $name = strtolower($name);

        if (isset($this->_menu[$name])) return true;
        else return parent::__isset($name);
    }


    /**
     * @param $name
     * @return mixed|null
     * @throws \yii\base\InvalidConfigException
     */
    public function getSubMenu($name)
    {
        if ($this->_menu === null) $this->setMenu();

        return isset($this->_menu[$name]) ? $this->_menu[$name] : null;
    }


    /**
     * @param null $key
     * @return array
     */
    public function getDBMenu($key = null)
    {
        if ($key) return [];
        /*
        if (app()->moduleManager->has('menu')) {

            return MenuItem::model()->getItems($key, 0);
        } else

            return [];
        */
        return [];
    }
}