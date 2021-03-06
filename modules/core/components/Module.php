<?php
namespace app\modules\core\components;

use app\modules\core\helpers\ModuleMenuTrait;
use app\modules\core\helpers\ModuleParamsTrait;
use app\modules\core\helpers\ModuleSettings;
use app\modules\core\helpers\ModuleSettingsTrait;
use app\modules\core\interfaces\ModuleMenuInterface;
use app\modules\core\interfaces\ModuleParamsInterface;
use app\modules\core\interfaces\ModuleSettingsInterface;
use \Yii;
use \app\modules\core\Module as CoreModule;
use \yii\base\Module as BaseModule;

/**
 * Абстрактный класс модулей
 *
 * Class Module
 * @package app\modules\core\components
 */
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

    protected $priority;

    /**
     * @var int
     */
    public $newDirMode = 0777;


    /**
     * @var int
     */
    public $newFileMode = 0777;



    /**
     * @return mixed
     */
    public function getVisualEditor()
    {
        /* @var CoreModule $module */
        $module = app()->getModule('core');
        return $module->getVisualEditor();
    }


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $module = $this->id;

        if (Yii::$app instanceof \yii\console\Application) {

            $this->controllerNamespace = 'app\\modules\\'.$module.'\\commands';
        } else {

            $this->controllerNamespace = 'app\\modules\\'.$module.'\\controllers';
        }

        $settings = ModuleSettings::model()->$module;

        if (count($settings)) {

            foreach ($settings as $key => $value) {

                if (property_exists($this, $key)) $this->$key = $value;
            }
        }
    }
}