<?php

namespace app\modules\cron\models;

use app\modules\core\components\ConsoleRunner;
use app\modules\cron\helpers\CronHelper;

/**
 * Class RunnerJob
 * @package app\modules\cron\models
 */
class RunnerJob extends Job{

    /**
     * Запуск задания
     * @return array
     */
    public function runJob(){
        $streamStatus = ConsoleRunner::STREAM_STATUS_SUCCESS;
        $jobOut = false;

        if($this->command && isset(CronHelper::getCommandActionList()[$this->command])){
            /** @var ConsoleRunner $runner */
            $runner = app()->consoleRunner;
            list($streamStatus, $jobOut) = $runner->run($this->command);
        }

        return [$streamStatus, $jobOut];
    }


    /**
     * Проверка на запуск по времени, установленного в параметрах
     * @param integer $curTime текущее время запуска
     * @return boolean
     */
    public function checkTime($curTime = 0){
        if(!$this->params){ return false; }

        /**
         * @param string $attrName название проверяемого атрибута
         * @param array $parsedData массив данных, полученный из $this->params
         * @param integer$curTime текущее время
         * @return bool
         */
        $checkParam = function($attrName, $parsedData, $curTime){
            return isset($parsedData[$attrName]) && in_array($curTime, $parsedData[$attrName]);
        };

        if(!$curTime){
            $curTime = time();
        }

        $status = false;
        if($data = CronHelper::parseCronParams($this->params)){
            $status =           $checkParam('minute',  $data, (integer)date('i', $curTime));
            $status = $status ? $checkParam('hour',    $data, (integer)date('i', $curTime)) : $status;
            $status = $status ? $checkParam('day',     $data, (integer)date('j', $curTime)) : $status;
            $status = $status ? $checkParam('month',   $data, (integer)date('n', $curTime)) : $status;
            $status = $status ? $checkParam('weekDay', $data, (integer)date('w', $curTime)) : $status;
        }

        return $status;
    }
}