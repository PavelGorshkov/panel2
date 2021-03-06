<?php
namespace app\modules\core;

use app\modules\core\auth\ModuleTask;
use app\modules\core\components\ConfigManager;
use app\modules\core\components\Module as ParentModule;
use app\modules\user\components\Roles;
use yii\helpers\ArrayHelper;

/**
 * Класс модуля core
 *
 * Class Module
 * @package app\modules\core
 */
class Module extends ParentModule
{
    public $uploadPath = 'uploads';

    public $imageUploadPath = 'images';

    public $coreCacheTime = 3600;

    public $copyright = 'ФГБОУ ВО "Марийский государственный универитет"';

    /**
     * @inheritdoc
     */
    public function init() {

        parent::init();

        $this->setVersion('1.0.0');
    }


    /**
     * @return array
     */
    public function getParamLabels()
    {
        return  [
            'uploadPath'=>'Путь загрузки файлов',
            'imageUploadPath'=>'Путь загрузки изображений',
        ];
    }


    /**
     * @return string
     */
    public static function Title() {

        return 'Ядро приложения';
    }


    /**
     * @return array
     */
    public function getMenuAdmin() {

        $items = [];

        $items = ArrayHelper::merge($items,
        [
            [
                'label' => 'Модули',
                'visible' => user()->can(ModuleTask::TASK)
            ],
            [
                'icon' => 'fa fa-fw fa-list-alt',
                'label' => 'Список модулей',
                'url' => $this->getMenuUrl('module/index'),
            ],
        ]);

        $items = ArrayHelper::merge($items, [
            [
                'label' => '',
                'options' => [
                    'role' => 'separator',
                    'class' => 'divider',
                    'visible' => user()->can(ModuleTask::TASK),
                ],
            ],
            [
                'icon' => 'fa fa-fw fa-cog',
                'label' => 'Настройки системы',
                'url' => ['/core/module/settings', 'module' => 'core'],
            ],
        ]);

        return [
            [
                'label' => '<span class="hidden-xs">Система</span>',
                'icon' => 'fa fa-fw fa-cog',
                'items' => $items,
                'visible' => user()->can(Roles::ADMIN),
            ]
        ];
    }


    /**
     *
     * @return bool
     */
    public function allFlush() {

        app()->cache->flush();

        (new ConfigManager(ConfigManager::ENV_WEB))->flushCache();

        app()->authManager->flush();

        return true;
    }
}
