<?php
namespace app\modules\core\helpers;

/**
 * Trait ModuleSettingsTrait
 * @package app\modules\core\helpers
 */
trait ModuleSettingsTrait
{
    /**
     * @var int|0
     */
    protected $sorting = null;

    /**
     * Выводит список модулей, от которых он будет зависеть
     *
     * @return array
     */
    public static function dependsOnModules()
    {

        return [];
    }
}