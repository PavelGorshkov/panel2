<?php
namespace app\modules\core\components;

use Yii;
use \yii\base\Module as BaseModule;


abstract class Module
    extends BaseModule
    implements ModuleSettingsInterface, ModuleParamsInterface, ModuleMenuInterface
{

    const CHECK_ERROR = 'danger';

    const CHECK_NOTICE = 'warning';

    const CHOICE_NO = 0;

    const CHOICE_YES = 1;

    const OBSERVER_URL = 'observer/index';

    use ModuleSettingsTrait;
    use ModuleParamsTrait;
    use ModuleMenuTrait;

    /**
     * @var int
     */
    public $newDirMode = 0777;


    /**
     * @var int
     */
    public $newFileMode = 0777;


    /**
     * @return string
     *
    public static function getSystemType()
    {
        return (self::isSystem()
            ? '<span class="label label-warning">Cистемный модуль</span>'
            : '<span class="label label-success">Модуль</span>');
    }
    */


    /**
     * @return mixed
     */
    public function getVisualEditor()
    {
        return app()->getModule('core')->getVisualEditor();
    }


    public function init()
    {
        parent::init();
        $module = $this->id;

        if (Yii::$app instanceof \yii\console\Application) {

            $this->controllerNamespace = 'app\\modules\\'.$module.'\\commands';
        } else {

            $this->controllerNamespace = 'app\\modules\\'.$module.'\\controllers';
        }

        // app()->migrator->updateToLatestSystem();
        // app()->migrator->updateToLatest($this->id);

       /*
        $settings = ModuleSettings::model()->$module;

        if (count($settings)) {

            foreach ($settings as $key => $value) {

                if (property_exists($this, $key)) $this->$key = $value;
            }
        }

       */
    }
}