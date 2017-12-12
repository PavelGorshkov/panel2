<?php
namespace app\modules\core\helpers;

trait ModuleTrait {

    public function getModule() {

        return app()->getModule('core');
    }
}