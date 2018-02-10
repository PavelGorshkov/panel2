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
        }

        return $this->_logger;
    }
}