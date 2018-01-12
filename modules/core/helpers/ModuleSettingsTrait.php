<?php
namespace app\modules\core\helpers;

/**
 * Trait ModuleSettingsTrait
 * @package app\modules\core\helpers
 */
trait ModuleSettingsTrait
{

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