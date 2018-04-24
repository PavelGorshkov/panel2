<?php
namespace app\modules\rating\helpers;

use app\modules\rating\Module;

/**
* Трейт для присвоения объекту свойста readonly модуля rating*
* Class ModuleTrait
* @package app\modules\rating\helpers
*
* @property-read Module $module
*/
trait ModuleTrait {

    /**
    * @return null|Module
    */
    public function getModule() {

        return app()->getModule('rating');
    }
}