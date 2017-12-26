<?php
/**
 * Created by PhpStorm.
 * User: Паштет
 * Date: 26.12.2017
 * Time: 22:28
 */

namespace app\modules\core\helpers;


interface ListHelperInterface
{
    /**
     * @return array
     */
    public static function getList();

    /**
     * @return array
     */
    public static function getHtmlList();
}