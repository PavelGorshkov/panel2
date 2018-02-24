<?php
namespace app\modules\core\interfaces;

/**
 * Interface RegisterCommandInterface
 * @package app\modules\core\interfaces
 */
interface RegisterCommandInterface
{
    /**
     * Получение списка команд в контроллере
     * @return array
     */
    public static function getList();
}