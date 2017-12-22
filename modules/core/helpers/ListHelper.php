<?php
namespace app\modules\core\helpers;

use yii\web\HttpException;

/**
 * Класс helper для работы с различными статусами модулей
 *
 * Class StatusHelper
 * @package app\modules\core\helpers
 */
abstract class ListHelper {

    const EMPTY_STATUS_HTML = '<span class="label label-default">*неизвестно*</span>';

    const EMPTY_STATUS = '*неизвестно*';

    public static function getValue($status, $asHtml = false) {

	    $class = get_called_class();

        $list = $asHtml
            ?$class::getHtmlList()
            :$class::getList();

        if (isset($list[$status])) return $list[$status];

        return $asHtml?self::EMPTY_STATUS_HTML:self::EMPTY_STATUS;
    }

    /**
     * @return array
     * @throws HttpException
     */
    public static function getList() {

        throw new HttpException(500, sprintf('Реализуйте метод "getList" в классе "%s"', get_called_class()));
    }

    /**
     * @return array
     * @throws HttpException
     */
    public static function getHtmlList() {

        throw new HttpException(500, sprintf('Реализуйте метод "getHtmlList" в классе "%s"', get_called_class()));
    }
}