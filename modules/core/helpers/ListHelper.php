<?php
namespace app\modules\core\helpers;

use app\modules\core\interfaces\ListHelperInterface;

/**
 * Класс helper для работы с различными статусами модулей
 *
 * Class StatusHelper
 * @package app\modules\core\helpers
 */
abstract class ListHelper implements ListHelperInterface {

    const EMPTY_STATUS_HTML = '<span class="label label-default">*неизвестно*</span>';

    const EMPTY_STATUS = '*неизвестно*';

    /**
     * @param string $key
     * @param bool $asHtml
     * @return string
     */
    public static function getValue($key, $asHtml = false) {

        /* @var ListHelper $class*/
        $class = get_called_class();

        $list = $asHtml
            ?$class::getHtmlList()
            :$class::getList();

        if (isset($list[$key])) return $list[$key];

        return $asHtml?self::EMPTY_STATUS_HTML:self::EMPTY_STATUS;
    }
}