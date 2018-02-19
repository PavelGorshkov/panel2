<?php

namespace app\modules\core\components;

use app\modules\core\helpers\LoggerTrait;
use app\modules\core\interfaces\LoggerInterface;
use yii\base\Component;
use yii\log\Logger;
use yii\web\HttpException;

/**
 *
 *
 * Class ConsoleRunner
 * @package app\modules\core\components
 */
class ConsoleRunner extends Component implements LoggerInterface{

    use LoggerTrait;

    const LOG_CATEGORY = 'console_runner';

    const STREAM_STATUS_ERROR = -1;
    const STREAM_STATUS_SUCCESS = 1;


    /**
     * Путь до файла консольного приложения
     * @var string
     */
    public $yiiPath;


    /**
     * Инициализация и добавление пути до yii
     */
    public function init()
    {
        parent::init();

        $this->initLogger();

        if (!$this->yiiPath) {

            $this->yiiPath = \Yii::getAlias("@app/yii");
        }
    }


    /**
     * Запуск на выполнение команды
     * 
     * @param string $command команда запуска (module/controller/action или controller/action)
     * @param array $params массив параметров
     * @param bool $isParallel флаг передачи выполнения в другой поток
     * @return array возвращает статус завершения потока и вевод запускаемой команды
     */
    public function run($command, $params = [], $isParallel = false){
        try {
            if(!$this->yiiPath){
                throw new HttpException('500', 'Path of yii or php not found.');
            }

            //Создание команды
            $params = $params ? implode(' ', $params) : '';
            $cmd = "{$this->yiiPath} $command $params";

            if (PHP_OS == 'WINNT' || PHP_OS == 'WIN32') {
                //Пока хз, как на винде запилить паралельно. Искать пока лень.
                $cmd = "start /b {$cmd}" . ($isParallel ? "" : "");
            } else {

                $cmd = "{$cmd} > /dev/null 2>" . ($isParallel ? "&1 &" : "/dev/null &");
            }

            //Выполнение команды
            $output = '';
            $handler = popen($cmd, 'r');
            $this->addLog(Logger::LEVEL_INFO, 'Запуск консольной команды ' . $cmd, self::LOG_CATEGORY);
            while (!feof($handler)) {
                $output .= fgets($handler);
            }

            //Проверка завершения работы команды
            if (pclose($handler) == self::STREAM_STATUS_ERROR) {
                throw new HttpException('500', 'Во время работы команды ' . $cmd . ' произошла ошибка.');
            }

            $output = trim($output);
            $this->addLog(
                Logger::LEVEL_INFO,
                'Команда ' . $cmd . ' выполнена.',
                self::LOG_CATEGORY);

            $this->addLog(
                Logger::LEVEL_INFO,
                'Результат работы команды (' . $cmd . '): ' . $output,
                self::LOG_CATEGORY);

            return [self::STREAM_STATUS_SUCCESS, $output];
        } catch (\Exception $error) {
            $this->addLog(
                Logger::LEVEL_ERROR,
                'Code: ' . $error->getCode() . ' Message: ' . $error->getMessage(),
                self::LOG_CATEGORY);

            return [self::STREAM_STATUS_ERROR, ''];
        }
    }


    /**
     * Set configTargets
     * @return mixed
     */
    public function setTargets(){
        return [
            [
                'class' => 'yii\log\FileTarget',
                'categories' => [self::LOG_CATEGORY],
                'logFile' => '@runtime/logs/' . self::LOG_CATEGORY . '_logs.log',
                'logVars' => [],
            ],
        ];
    }
}