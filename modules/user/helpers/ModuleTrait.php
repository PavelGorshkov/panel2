<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 12.12.2017
 * Time: 17:24
 */

namespace app\modules\user\helpers;


trait ModuleTrait {

    public function getModule() {

        return app()->getModule('user');
    }
}