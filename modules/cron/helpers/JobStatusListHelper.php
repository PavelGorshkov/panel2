<?php

namespace app\modules\cron\helpers;

use app\modules\core\helpers\ListHelper;

/**
 * Статус активности команды
 * @package app\modules\cron\helpers
 */
class JobStatusListHelper extends ListHelper{

    const STATUS_ACTIVE = 1;
    const STATUS_NOT_ACTIVE = 0;


    /**
     * @* return array
     */
    public static function getList(){
        return [
            self::STATUS_NOT_ACTIVE => 'Не активно',
            self::STATUS_ACTIVE => 'Aктивно',
        ];
    }


    /**
     * @return array
     */
    public static function getHtmlList(){
        return [
            self::STATUS_NOT_ACTIVE => '<i class="fa fa-fw fa-minus-square-o text-warning" title="Не активированный"></i>',
            self::STATUS_ACTIVE => '<i class="fa fa-fw text-success fa-plus-square-o" title="Активированный"></i>',
        ];
    }
}