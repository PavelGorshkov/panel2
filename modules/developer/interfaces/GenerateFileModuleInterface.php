<?php
namespace app\modules\developer\interfaces;


/**
 * Interface GenerateFileModuleInterface
 * @package app\modules\developer\interfaces
 */
interface GenerateFileModuleInterface {

    /**
     * Генерация php afqkf
     * @return mixed
     */
    public function generate();

    /**
     * @param string $module
     * @return void
     */
    public function setModule($module);

    /**
     * @return string
     */
    public function getSuccessMessage();
}