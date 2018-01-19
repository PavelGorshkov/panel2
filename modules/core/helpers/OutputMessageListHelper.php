<?php
namespace app\modules\core\helpers;

use yii\helpers\Console;


class OutputMessageListHelper extends ListHelper
{
    const SUCCESS = 3;

    const WARNING = 2;

    const INFO = 0;

    const ERROR = 1;

    /**
     * @return array
     */
    public static function getList()
    {
        return [
            self::INFO => Console::FG_BLUE,
            self::ERROR => Console::FG_RED,
            self::WARNING => Console::FG_YELLOW,
            self::SUCCESS => Console::FG_GREEN,
        ];
    }

    /**
     * @return array
     */
    public static function getHtmlList()
    {
        return [
            self::INFO => '#00c0ef',
            self::ERROR => '#dd4b39',
            self::WARNING => '#f39c12',
            self::SUCCESS => '#00a65a',
        ];
    }
}