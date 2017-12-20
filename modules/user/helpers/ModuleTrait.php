<?php
namespace app\modules\user\helpers;

use app\modules\user\Module;

/**
 * Трейт для присвоения объекту свойста readonly модуля user
 *
 * Class ModuleTrait
 * @package app\modules\user\helpers
 *
 * @property-read Module $module
 */
trait ModuleTrait {

    /**
     * @return Module $module
     */
    public function getModule() {

        return app()->getModule('user');
    }
}