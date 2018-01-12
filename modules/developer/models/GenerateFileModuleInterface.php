<?php
namespace app\modules\developer\models;


/**
 * Interface GenerateFileModuleInterface
 * @package app\modules\developer\models
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