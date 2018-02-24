<?php

namespace app\modules\core\helpers;

use app\modules\core\components\LogDispatcher;
use yii\base\Exception;
use yii\di\ServiceLocator;

/**
 * Trait LoggerTrait
 * @package app\modules\core\helpers
 * @method setTargets() array
 */
trait LoggerTrait
{
    /**
     * @var LogDispatcher|null
     */
    protected $logger = null;


    /**
     * @throws Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function initLogger()
    {
        $targets = $this->setTargets();

        if (is_array($targets) && !empty($targets)) {

            if (count($targets) == count($targets, true)) {

                $targets = [$targets];
            }

            $locator = new ServiceLocator();
            $id = uniqid("log_");

            $locator->set($id, [
                'class' => LogDispatcher::class,
                'traceLevel' => 0,
                'targets' => $targets,
            ]);

            $this->logger = $locator->get($id);

        } else {
            throw new Exception("Должен существововать хотя бы 1 targets.");
        }
    }


    /**
     * Add logg method
     * @param integer $level logLevel
     * @param string $message LogMessage
     * @param string $category LogCategory
     */
    public function addLog($level, $message, $category)
    {

        $this->logger->getLogger()->log($message, $level, $category);
    }
}