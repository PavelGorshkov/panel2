<?php
namespace app\modules\core\helpers;

use app\modules\core\Module;

/**
 * Трейт для присвоения объекту свойста readonly модуля core
 *
 * Class ModuleTrait
 * @package app\modules\core\helpers
 *
 * @property-read Module $module
 */
trait ModuleTrait {

    public function getModule() {

        return app()->getModule('core');
    }
}