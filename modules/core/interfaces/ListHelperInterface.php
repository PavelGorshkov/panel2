<?php
namespace app\modules\core\interfaces;

/**
 * Interface ListHelperInterface
 * @package app\modules\core\interfaces
 */
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