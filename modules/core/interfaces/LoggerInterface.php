<?php
namespace app\modules\core\interfaces;

/**
 * Interface LoggerInterface
 * @package app\modules\core\interfaces
 */
interface LoggerInterface
{
    /**
     * initLogger method
     */
    public function initLogger();

    /**
     * add log message method
     * @param integer $level logLevel
     * @param string $message logMessage
     * @param string $category logcategory
     */
    public function addLog($level, $message, $category);

    /**
     * Set configTargets
     * @return mixed
     */
    public function setTargets();
}