<?php
namespace app\modules\user\helpers;

use app\modules\core\helpers\ListHelper;

/**
 * Класс helper для работы со списком статусов подтверждения email пользователя
 *
 * Class EmailConfirmHelper
 * @package app\modules\user\helpers
 */
class EmailConfirmStatusHelper extends ListHelper {

    const EMAIL_CONFIRM_NO = 0;

    const EMAIL_CONFIRM_YES = 1;

    const EMPTY_STATUS_HTML = '<span class="label label-default">*неизвестно*</span>';

    const EMPTY_STATUS = '*неизвестно*';


    public static function getList()
    {
        return [
            self::EMAIL_CONFIRM_YES => 'Да',
            self::EMAIL_CONFIRM_NO => 'Нет',
        ];
    }


    public static function getHtmlList()
    {
        return [
            self::EMAIL_CONFIRM_YES => '<span class="label label-success">Подтвержден</span>',
            self::EMAIL_CONFIRM_NO => '<span class="label label-danger">Не подтвержден</span>',
        ];
    }
}