<?php
namespace app\modules\cron\helpers;

use app\modules\core\interfaces\RegisterCommandInterface;

/**
 * Вспомогательный класс для работы с модулем.
 * Используемые параметры: minute, hour, day, month, weekDay
 *
 * @package app\modules\cron\helpers
 */
class CronHelper{

    /**
     * Параметры крона
     * @var array
     */
    private static $_attrs = [
        'minute' => 'Минуты',
        'hour' => 'Часы',
        'day' => 'Дни',
        'month' => 'Месяцы',
        'weekDay' => 'Дни недели'
    ];

    /**
     * Список месяцев
     * @var array
     */
    private static $_month = [1=>'Январь', 'Февраль', 'Март', 'Апрель','Май', 'Июнь', 'Июль', 'Август','Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];

    /**
     * Список дней недели
     * @var array
     */
    private static $_day = [0=>'Воскресение', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'];


    /**
     * Получение списка атрибутов
     * @return array
     */
    public static function getTimeParamsList(){
        return self::$_attrs;
    }


    /**
     * @param $attr
     * @return string
     */
    public static function getTimeParamTitle($attr){
        return isset(self::$_attrs[$attr]) ? self::$_attrs[$attr] : '';
    }


    /**
     * Получение значений для каждого атрибута времени cron-модуля
     * @param string $attr
     * @return array
     */
    public static function getTimeElementData($attr){
        $attrValues = [];
        if(isset(self::$_attrs[$attr])){
            /**
             * Функция заполнения данных элемента
             * @param integer $start
             * @param integer $end
             * @param integer $step
             * @return array
             */
            $functionCreateValues = function($start, $end, $step){
                $data = [];
                for($i = $start; $i < $end; $i = $i + $step){ $data[$i] = $i; }
                return $data;
            };

            switch ($attr) {
                case 'minute' : $attrValues = $functionCreateValues(0, 60, 5); break;
                case 'hour'   : $attrValues = $functionCreateValues(0, 24, 1); break;
                case 'day'    : $attrValues = $functionCreateValues(1, 32, 1); break;
                case 'month'  : $attrValues = self::$_month; break;
                case 'weekDay': $attrValues = self::$_day; break;
            }
        }

        return $attrValues;
    }


    /**
     * Разбивает строку параметров в массив атрибутов
     * @param string $params
     * @return array
     */
    public static function parseCronParams($params){
        $attrs = [];
        $parts = explode(' ', $params);

        if(count($parts) == count(self::$_attrs)){
            $i = 0;
            foreach(array_keys(self::$_attrs) as $attr){
                $values = explode(',', $parts[$i]);
                $attrs[$attr] = ($values[0] == '*') ? array_keys(self::getTimeElementData($attr)) : $values;
                $i++;
            }
        }

        return $attrs;
    }


    /**
     * Получение списка команд
     * @return array
     */
    public static function getCommandActionList(){
        $list = [];
        foreach(app()->moduleManager->getListEnabledModules() as $module){

            $commandPath = \Yii::getAlias(sprintf("@app/modules/%s/commands", $module));
            $nameSpace = '\\app\\modules\\'.$module.'\\commands\\';

            foreach (new \GlobIterator($commandPath.'/*.php') as $file) {

                $className = $nameSpace.$file->getBaseName('.php');
                if (!class_exists($className)) {
                    continue;
                }

                $reflection = new \ReflectionClass($className);
                if($reflection->implementsInterface('app\modules\core\interfaces\RegisterCommandInterface')){

                    $command = mb_strtolower($file->getBaseName('.php'));
                    $command= str_replace('controller', '', $command);

                    /* @var RegisterCommandInterface $className */
                    foreach ($className::getList() as $action => $title){
                        $list[$module.'/'.$command.'/'.$action] = $title;
                    }
                }
            }
        }

        return $list;
    }


    /**
     * Получение названия экшена команды
     * @param string $command
     * @return string
     */
    public static function getCommandActionTitle($command){
        $list = self::getCommandActionList();
        return isset($list[$command]) ? $list[$command] : '';
    }
}