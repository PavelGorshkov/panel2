<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 07.12.2017
 * Time: 8:38
 */

namespace app\modules\core\components;


interface ModuleInterface {

    /**
     * Обязательный
     * @return null
     */
    public static function Title();


    /**
     * Выводит список модулей, от которых он будет зависеть
     *
     * @return []
     */
    public static function dependsOnModules();


}