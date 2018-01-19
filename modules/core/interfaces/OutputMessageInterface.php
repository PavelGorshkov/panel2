<?php
namespace app\modules\core\interfaces;

/**
 * Interface OutputMessageInterface
 * @package app\modules\core\interfaces
 */
interface OutputMessageInterface
{
    /**
     * @param string $message
     * @param integer|null $type
     */
    public function addMessage($message, $type = null);


    /**
     * @return string
     */
    public function getHtml();


    /**
     * @return array
     */
    public function getConsole();
}