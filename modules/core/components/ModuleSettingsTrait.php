<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 07.12.2017
 * Time: 9:09
 */

namespace app\modules\core\components;


trait ModuleSettingsTrait {

    /**
     * Выводит список модулей, от которых он будет зависеть
     *
     * @return array
     */
    public static function dependsOnModules() {

        return [];
    }

}