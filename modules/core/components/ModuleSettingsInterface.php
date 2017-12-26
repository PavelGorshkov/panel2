<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 07.12.2017
 * Time: 8:38
 */

namespace app\modules\core\components;


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