<?php
namespace app\modules\core\helpers;

/**
 * Класс helper для работы с различными статусами модулей
 *
 * Class StatusHelper
 * @package app\modules\core\helpers
 */
abstract class ListHelper implements ListHelperInterface {

    const EMPTY_STATUS_HTML = '<span class="label label-default">*неизвестно*</span>';

    const EMPTY_STATUS = '*неизвестно*';

    public static function getValue($status, $asHtml = false) {

        /* @var ListHelper $class*/
        $class = get_called_class();

        $list = $asHtml
            ?$class::getHtmlList()
            :$class::getList();

        if (isset($list[$status])) return $list[$status];

        return $asHtml?self::EMPTY_STATUS_HTML:self::EMPTY_STATUS;
    }
}