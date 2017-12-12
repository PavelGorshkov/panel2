<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 12.12.2017
 * Time: 13:53
 */

namespace app\modules\user\components;


abstract class RBACItem implements  RBACItemInterface {

    public $types = null;

    public function getRuleNames() {}

    public function getTitle($item) {

        $list = $this->titleList();

        return iisset($list[$item])?$list[$item]:'';
    }

    public static function getDescription($role) {

        $class = get_called_class();

        $class = new $class;

        if (!($class instanceof RBACItemInterface)) return null;

        return $class->getTitle($role);
    }
}