<?php
namespace app\modules\core\helpers;

use yii\base\Exception;
use yii\di\ServiceLocator;
use yii\log\Dispatcher;

/**
 * Trait LoggerTrait
 * @package app\modules\core\helpers
 * @method setTargets() array
 */
trait LoggerTrait
{
    /**
     * @var Dispatcher:null
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
                'class' => Dispatcher::className(),
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