<?php
namespace app\modules\rating;

use app\modules\core\components\Module as ParentModule;

/**
 * rating module definition class
 * Class Module
 * @package app\modules\rating */
class Module extends ParentModule
{
    /**
    * @return string
    */
    public static function Title() {

        return 'Рейтинг';
    }
}
