<?php
namespace app\modules\core\components;

use yii\log\Dispatcher;
use yii\log\Logger;

/**
 * Class LogDispatcher
 * @package app\modules\core\components
 */
class LogDispatcher extends Dispatcher
{
    /**
     * @var Logger the logger.
     */
    private $_logger;

    public function getLogger()
    {
        if ($this->_logger === null) {

            $this->_logger = new Logger();
            $this->_logger->dispatcher = $this;
        }

        return $this->_logger;
    }


    public function __destruct()
    {
        $this->getLogger()->flush();
    }
}