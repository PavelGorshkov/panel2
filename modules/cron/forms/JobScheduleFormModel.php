<?php

namespace app\modules\cron\forms;

use app\modules\core\components\FormModel;
use app\modules\core\interfaces\SaveModelInterface;
use app\modules\cron\helpers\JobStatusListHelper;
use app\modules\cron\helpers\CronHelper;
use app\modules\cron\interfaces\TimeParamInterface;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class JobScheduleFormModel
 * @package app\modules\cron\forms
 *
 * @property-read boolean $isNewRecord
 */
class JobScheduleFormModel extends FormModel implements SaveModelInterface, TimeParamInterface
{

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $command;

    /**
     * @var int
     */
    public $is_active = JobStatusListHelper::STATUS_NOT_ACTIVE;

    /**
     * @var string
     */
    public $params;

    /**
     * @var array
     */
    public $month = [];

    /**
     * @var array
     */
    public $day = [];

    /**
     * @var array
     */
    public $weekDay = [];

    /**
     * @var array
     */
    public $hour = [];

    /**
     * @var array
     */
    public $minute = [];


    /**
     * Проверка на новую запись
     * @return bool
     */
    public function getIsNewRecord(){
        return $this->id === null;
    }


    /**
     * @return array
     */
    public function attributeLabels(){
        return [
            'id' => 'ID',
            'title' => 'Заголовок задания',
            'command' => 'Команда запуска',
            'params' => 'Параметры запуска',
            'is_active' => 'Активность',
            'month' => 'Месяц',
            'day'  => 'День',
            'weekDay'=>'День недели',
            'hour' => 'Час',
            'minute' => 'Минута'
        ];
    }


    /**
     * @return array
     */
    public function rules(){
        return [
            [['command', 'month', 'day', 'weekDay', 'hour', 'minute'], 'required'],
            ['is_active', 'in', 'range'=>array_keys(JobStatusListHelper::getList())],
            [['id', 'params', 'is_active'], 'safe'],
        ];
    }


    /**
     * @param array $values
     * @param bool $safeOnly
     */
    public function setAttributes($values, $safeOnly = true){
        //Преобразование строки крон-параметров в отрибуты модели
        if(isset($values['params'])){
            $params = CronHelper::parseCronParams($values['params']);
            $values = ArrayHelper::merge($values, $params);
        }

        parent::setAttributes($values, $safeOnly);
    }


    /**
     * @param Model $model
     *
     * @return boolean
     */
    public function processingData(Model $model){
        //Создание params из minute, hour, day, month, weekDay
        $params = [];
        foreach (array_keys(CronHelper::getTimeParamsList()) as $attr){
            if(!$this->$attr || count($this->$attr) == count(CronHelper::getTimeElementData($attr))){
                $this->$attr = ['*'];
            }
            $params[] = implode(',', $this->$attr);
        }
        $this->params = implode(' ', $params);

        $model->setAttributes($this->getAttributes());

        return $model->save();
    }


    /**
     * @param string $attr
     * @return array
     */
    public function getTimeData($attr){
        return CronHelper::getTimeElementData($attr);
    }


    /**
     * @return string
     */
    public function formName(){
        return 'job-schedule';
    }
}