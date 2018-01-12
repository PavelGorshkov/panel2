<?php
namespace app\modules\core;

use app\modules\core\auth\ModuleTask;
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
}
