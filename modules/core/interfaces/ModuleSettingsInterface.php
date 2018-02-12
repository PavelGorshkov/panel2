<?php
namespace app\modules\core\interfaces;

/**
 * Interface ModuleSettingsInterface
 * @package app\modules\core\interfaces
 */
interface ModuleSettingsInterface {

    /**
     * Обязательный
     * @return null
     */
    public static function Title();


    /**
     * Выводит список модулей, от которых он будет зависеть
     *
     * @return array
     */
    public static function dependsOnModules();
}