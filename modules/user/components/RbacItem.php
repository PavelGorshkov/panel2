<?php
namespace app\modules\user\components;

use yii\web\HttpException;

/**
 * Class RBACItem
 * @package app\modules\user\components
 *
 */
abstract class RBACItem implements  RBACItemInterface {

    const TASK = '';

    public $types = null;

    public function getRuleNames() {return null;}

    public function getTitle($item) {

        $list = $this->titleList();

        return isset($list[$item])?$list[$item]:'';
    }

    public static function getDescription($role) {

        $class = get_called_class();

        $class = new $class;

        if (!($class instanceof RBACItemInterface)) return null;

        return $class->getTitle($role);
    }

    public function getTitleTask()
    {
        if (self::TASK) return self::TASK;

        else throw new HttpException(500, 'Create method "public function getTitleTask()" in class "'.get_called_class().'"');
    }
}