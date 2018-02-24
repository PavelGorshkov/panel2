<?php
namespace app\modules\core\interfaces;

/**
 * Интерфейс модуля для работы меню приложения
 *
 * Interface ModuleMenuInterface
 * @package app\modules\core\components
 */
interface ModuleMenuInterface
{

    /**
     * @return array
     */
    public function getMenuAdmin();


    /**
     * @return array
     */
    public function getMenuMain();


    /**
     * @return array
     */
    public function getMenuRedactor();


}