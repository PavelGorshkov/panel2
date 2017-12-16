<?php
namespace app\modules\core\components;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class MenuManager extends Component {

    const MENU_DATABASE = 'database';

    const MENU_MODULE = 'module';

    const MENU_STATIC = 'file';

    const TYPE_ADMIN = 'admin';

    const TYPE_MAIN = 'main';

    const TYPE_REDACTOR = 'redactor';

    protected $_menu = null;

    public $type = [
        self::TYPE_ADMIN=>self::MENU_MODULE,
        self::TYPE_MAIN=>self::MENU_MODULE,
        self::TYPE_REDACTOR=>self::MENU_MODULE,
    ];

    protected $internalType = null;

    public function init() {

        parent::init();

        if ($this->type !== null && count($this->type)) {

            foreach ($this->type as $key => $type) {

                $this->internalType[$type][] = $key;
            }
        }
    }


    public function getData() {

        if ($this->_menu === null) $this->setMenu();

        return $this->_menu;
    }


    public function setMenu() {

        $this->_menu = app()->cache->get('cacheMenu');

        if ($this->_menu === false ) {

            $this->prepareMenu();
        }
    }


    public function clearCache() {

        app()->cache->delete('cacheMenu');
    }


    protected function prepareMenu()
    {
        $menu = [];

        foreach ($this->internalType as $type => $types) {

            switch ($type) {

                case self::MENU_STATIC:

                    foreach ($types as $key) {

                        $file = Yii::getAlias('@app/config/menu'.$key.'.php');
                        if (file_exists($file)) $menu[$key] = include($file);
                    }

                    break;

                case self::MENU_MODULE:

                    $menu = ArrayHelper::merge($menu, app()->moduleManager->getMenu($types));

                    break;

                case self::MENU_DATABASE:

                    if (!app()->hasModule('menu')) break;

                    else {

                        foreach ($types as $key) {$menu[$key] = $this->getDBMenu($key);}
                    }

                    break;
            }
        }

        $this->_menu = $menu;

        app()->cache->set('cacheMenu', $menu, 0);
    }


    public function __get($name) {

        if ($this->_menu === null) $this->setMenu();

        $name = strtolower($name);

        if (isset($this->_menu[$name])) {

            return $this->getSubMenu($name);

        } else return parent::__get($name);
    }


    public function __isset($name) {

        if ($this->_menu === null) $this->setMenu();

        $name = strtolower($name);

        if (isset($this->_menu[$name])) return true;
        else return parent::__isset($name);
    }


    public function getSubMenu($name) {

        if ($this->_menu === null) $this->setMenu();

        return isset($this->_menu[$name])?$this->_menu[$name]:null;
    }


    public function getDBMenu($key) {

        /*
        if (app()->moduleManager->has('menu')) {

            return MenuItem::model()->getItems($key, 0);
        } else

            return [];
        */
    }


}