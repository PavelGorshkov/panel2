<?php

namespace app\modules\core\components;

use app\modules\core\helpers\LoggerTrait;
use app\modules\core\interfaces\LoggerInterface;
use SoapClient;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\log\FileTarget;
use yii\web\ServerErrorHttpException;

/**
 * Class WebService
 * @package app\modules\core\components
 */
class WebService extends Component implements LoggerInterface
{
    use LoggerTrait;

    public $options = [];

    /**
     * @var SoapClient|null;
     */
    protected $_providers = [];

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function init()
    {
        ini_set('soap.wsdl_cache_enabled', 0);
        ini_set('soap.wsdl_cache_ttl', 0);

        parent::init();
        if (!extension_loaded('soap'))
            throw new Exception("soap extension library not installed");


        if (!is_array($this->options) || empty($this->options)) {

            throw new ServerErrorHttpException('The options must be arrays and not empty');
        }

        if (count($this->options) === count($this->options, true)) {

            throw new ServerErrorHttpException('The options must be arrays');
        }

        $this->initLogger();
    }


    /**
     * @param string $provider
     * @return WebServiceInstance
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function getProvider($provider)
    {
        if (!isset($this->_providers[$provider])) {

            $this->initProvider($provider);
        }

        return $this->_providers[$provider];
    }


    /**
     * @param  string $provider
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function initProvider($provider)
    {

        if (!isset($this->options[$provider])) {

            throw new ServerErrorHttpException('Not found options for provider "' . $provider . '"');
        }

        /** @var $instance WebService */
        $this->_providers[$provider] = \Yii::createObject(
            ArrayHelper::merge(
                [
                    'class' => WebServiceInstance::class,
                    'logger' => $this->logger,
                    'category' => $provider
                ],
                $this->options[$provider]
            ));
    }


    /**
     * InitLoggerConfig
     * @return array configTargets
     */
    public function setTargets()
    {
        return [
            [
                'class' => FileTarget::class,
                'categories' => array_keys($this->options),
                'logFile' => '@runtime/logs/webservice.log',
                'logVars' => [],
            ]
        ];
    }
}