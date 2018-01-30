<?php
namespace app\modules\core\components;

use Exception;
use SoapClient;
use yii\base\BaseObject;
use yii\log\Dispatcher;
use yii\log\Logger;

/**
 * Class WebServiceInstance
 * @package app\modules\core\components
 */
class WebServiceInstance extends BaseObject
{
    /**
     * @var Dispatcher
    public $logger;

    public $isLogger = false;

    /**
     * @var string LoggerCategory
     */
    public $category = '';

    /**
     * @var string Url to wsdl
     */
    public $url;

    /**
     * @var string login for autentication
     */
    public $login;

    /**
     * @var string password for autentication
     */
    public $password;

    /**
     * @var int SOAP Version default "2"
     */
    public $versionSoap = SOAP_1_2;

    /**
     * @var int wsdl cache
     */
    public $cache_wsdl = WSDL_CACHE_NONE;

    /**
     * @var int use csi array type
     */
    public $soap_use_xsi = SOAP_USE_XSI_ARRAY_TYPE;

    /**
     * @var array|null avalible wsFunctionList;
     */
    protected $wsFunctionList = null;

    /**
     * @var \SoapClient
     */
    protected $instance;

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function init()
    {
        $this->setConnectionInstance();
        $this->wsFunctionList = $this->initAvalibleWsFunction();
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


    /**
     * Add logg method
     * @param integer $level Logger const
     * @param string $message any message
     */
    private function setLog($level, $message)
    {
        if ($this->isLogger) {

            $this->addLog($level, $message, $this->category);
        }
    }

    /**
     * Попытка соединения с веб-сервисом, при неудачной попытке вызывается исключение.
     * @throws Exception
     */
    private function setConnectionInstance()
    {
        $this->setLog(Logger::LEVEL_INFO, "Попытка соединения с веб-сервисом " . $this->url);
        $this->setLog(Logger::LEVEL_INFO,
            "Данные конфигурации. логин: " . $this->login .
            " password:" . $this->password .
            " soapVersion:" . $this->versionSoap .
            " cachewsdl:" . $this->cache_wsdl .
            " features: " . $this->soap_use_xsi);

        try {

            $this->instance = new SoapClient($this->url, [
                'login' => $this->login,
                'password' => $this->password,
                'soap_version' => $this->versionSoap,
                'cache_wsdl' => $this->cache_wsdl,
                'trace' => true,
                'features' => $this->soap_use_xsi,
            ]);

        } catch (\SoapFault $sf) {

            $this->setLog(Logger::LEVEL_ERROR, "Соединение с веб-сервисом не установлено! " . $sf->getMessage());
            error_clear_last();
            throw new Exception("Соединение с веб-сервисом не установлено!");
        }

    }


    /**
     * Получение списка доступных функций веб-сервиса
     * @return array|null funcList
     */
    public function getWsFunctionList()
    {
        return $this->wsFunctionList;
    }


    /**
     * Инициализация доступных функций веб-сервиса
     *
     * @return array|null wsFuncList
     */
    private function initAvalibleWsFunction()
    {

        $functions = $this->instance->__getFunctions();
        $array = [];

        foreach ($functions as $key => $f_val) {

            $data = explode(" ", $f_val);
            $array[$key] = str_replace('Response', '', $data[0]);
        }

        $array = array_flip($array);
        $this->setLog(Logger::LEVEL_INFO, "Получение списка доступных функций. " . json_encode($array));
        return empty($array) ? null : $array;
    }


    /**
     * @param string $name funcName
     * @param array $arguments funcArgs
     * @return array|mixed data
     * @throws Exception error
     */
    public function __call($name, $arguments)
    {
        if (!empty($this->wsFunctionList[$name])) {
            $this->setLog(Logger::LEVEL_INFO, "Попытка вызова функции веб-сервиса " . $name . " c аргументами: " . json_encode($arguments));
            $data = $this->implementsFunction($name, $arguments);
            $this->setLog(Logger::LEVEL_INFO, "Веб-сервис вернул результат функции " . $name);
            return json_decode(json_encode($data), 1);

        } else {
            $this->setLog(Logger::LEVEL_ERROR, "Веб-сервис не содержит функцию " . $name);
            throw new Exception('Веб сервис не содержит функцию ' . $name);
        }
    }


    /**
     * @param string $name funcName
     * @param null|array $arguments funcArgs
     * @return mixed data
     * @throws Exception error
     */
    private function implementsFunction($name, array $arguments = null)
    {
        $args = is_array($arguments) ? array_shift($arguments) : null;

        try {
            $data = $this->instance->$name($args);
            return $data;

        } catch (\Exception $ex) {
            $this->setLog(Logger::LEVEL_ERROR, "Ошибка функции " . $name . ". " . $ex->getMessage());
            throw new Exception('Ошибка функции:' . $name);
        }
    }
}