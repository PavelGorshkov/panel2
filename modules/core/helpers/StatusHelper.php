<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 15.12.2017
 * Time: 9:21
 */

namespace app\modules\core\helpers;


use yii\web\HttpException;

abstract class StatusHelper {

    const EMPTY_STATUS_HTML = '<span class="label label-default">*неизвестно*</span>';

    const EMPTY_STATUS = '*неизвестно*';

    public static function getStatus($status, $asHtml = false) {

        $list = $asHtml
            ?self::getStatusHtmlList()
            :self::getStatusList();

        if (isset($list[$status])) return $list[$status];

        return $asHtml?self::EMPTY_STATUS_HTML:self::EMPTY_STATUS;
    }

    public static function getStatusList() {

        throw new HttpException(500, sprintf('Реализуйте метод "getStatusList" в классе "%s"', get_called_class()));
    }

    public static function getStatusHtmlList() {

        throw new HttpException(500, sprintf('Реализуйте метод "getStatusHtmlList" в классе "%s"', get_called_class()));
    }
}