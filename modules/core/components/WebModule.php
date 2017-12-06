<?php
namespace app\modules\core\components;

use \yii\base\Module as BaseModule;


class WebModule extends BaseModule {

    const CHECK_ERROR = 'danger';

    const CHECK_NOTICE = 'warning';

    const CHOICE_NO = 0;

    const CHOICE_YES = 1;

    const OBSERVER_URL = 'observer/index';

    public static $logCategory = 'application';

    /**
     * @var int
     */
    public $newDirMode = 0777;


    /**
     * @var int
     */
    public $newFileMode = 0777;


    public static function dependsOnModules()
    {
        return [];
    }


    public function getMenuAdmin()
    {
        return [];
    }


    public function getMenuMain()
    {
        return [];
    }


    public function getMenuRedactor()
    {
        return [];
    }


    public function getMenuUrl($url = self::OBSERVER_URL) {

        return [
            '/'.$this->id.'/'.trim($url, '/')
        ];
    }


    /**
     * @return array
     */
    public function getParamGroups()
    {
        return [];
    }


    /**
     * @return array
     */
    public function getParamLabels()
    {
        return [];
    }


    /**
     * @return array
     */
    public function getParamsDropdown()
    {
        return [];
    }


    /**
     * @return string
     */
    public static function getSystemType()
    {
        return (self::isSystem()
            ? '<span class="label label-warning">Cистемный модуль</span>'
            : '<span class="label label-success">Модуль</span>');
    }

    /**
     * Обязательный
     * @return null
     */
    public static function getTitle() {

        return null;
    }


    /**
     * @return mixed
     */
    public function getVisualEditor()
    {
        return app()->getModule('core')->getVisualEditor();
    }


    public function init()
    {
        $module = $this->id;

        // app()->migrator->updateToLatestSystem();
        // app()->migrator->updateToLatest($this->id);

        $settings = ModuleSettings::model()->$module;

        if (count($settings)) {

            foreach ($settings as $key => $value) {

                if (property_exists($this, $key)) $this->$key = $value;
            }
        }

        parent::init();
    }
}