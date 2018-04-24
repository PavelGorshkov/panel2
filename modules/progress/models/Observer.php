<?php

namespace app\modules\progress\models;

use app\modules\progress\helpers\Digest;
use yii\db\ActiveQuery;
use yii\widgets\Menu;

/**
 * Class Observer
 * @package app\modules\progress\models
 */
class Observer{

    const ALL_FORMS = 'forms';

    const WEIGHT_EXCELLENT = 75;
    const WEIGHT_GOOD = 50;


    /**
     * Получение класса в зависимости от оценки
     * @param integer|float $mark
     * @return string
     */
    public static function getClass($mark){
        if($mark >= self::WEIGHT_EXCELLENT){
            return 'success';
        }

        if($mark >= self::WEIGHT_GOOD){
            return 'warning';
        }

        return 'danger';
    }


    /**
     * Получение годов
     * @param integer|null $year
     * @return array
     */
    public function getRangeYear($year = null){
        if(!($max = Statistic::find()->max('year'))){
            return [0, 0, 0];
        }

        $min = Statistic::find()->min('year');

        if($year === null){
            $year = $max;
        }

        return [$min, $max, $year];
    }


    /**
     * Получение id меню форм обучения
     * @return string
     */
    public function getFormMenuId(){
        return 'menu_form_widget_id';
    }


    /**
     * Получение меню для форм обучения
     * @param $year
     * @return null|string
     * @throws \Exception
     */
    public function getFormMenuWidget($year){
        //$year = date('n')  < 9 ? date('Y') - 1: (integer)date('Y');

        //Получение всех форм обучения, для студентов у которых есть статистика
        $data = Statistic::find()
            ->select(['student_id'])
            ->with(['student' => function($query){ /** @var ActiveQuery $query */ $query->select(['form', 'id']);}])
            ->where('year = :year', [':year' => $year])
            ->asArray()
            ->all();

        $forms = [];
        foreach($data as $form){
            if($form['student'] && !isset($forms[$form['student']['form']])){
                $forms[$form['student']['form']] = Digest::getItemValue(Dictionary::DICTIONARY_FORM, $form['student']['form']);
            }
        }

        //Заполнение данными виджета
        $items = [];
        if($forms){
            $current = key($forms);
            foreach($forms as $id => $title){
                $items[] = [
                    'template'=> '<a href="{url}" data-year='.$year.' data-form='.$id.'>{label}</a>',
                    'label'=>$title,
                    'url'=>'javascript:void(0);',
                    'active' => $current == $id
                ];
            }

            //Дополнительный item
            if(count($items) > 1){
                $items[] = [
                    'template'=> '<a href="{url}" data-year='.$year.' data-form='.self::ALL_FORMS.'>{label}</a>',
                    'label'=>'Все формы',
                    'url'=>'javascript:void(0);'
                ];
            }
        }

        $widget = null;
        if($items){
            $widget = Menu::widget(['items'=>$items, 'options'=>['class'=>'nav nav-pills', 'id'=>$this->getFormMenuId()], 'encodeLabels'=>true]);
        }

        return $widget;
    }


    /**
     * Получение статистики по факультетам
     * @param $year
     * @param $form
     * @return array
     */
    public function getUnitStatistic($year, $form){
        $data = Statistic::find()
            ->select(['student_id', 'average'])
            ->with(['student' => function($query){ /** @var ActiveQuery $query */ $query->select(['form', 'id', 'faculty']);}])
            ->where('year = :year', [':year' => $year])
            ->asArray()
            ->all();

        $statistic = [];
        foreach($data as $item){
            if($item['student']){
                if(($form == self::ALL_FORMS) || ($form == $item['student']['form'])){

                    if(!isset($statistic[$item['student']['faculty']])){
                        $statistic[$item['student']['faculty']] = [
                            'title' => Digest::getItemValue(Dictionary::DICTIONARY_FACULTY, $item['student']['faculty']),
                            'count' => 0,
                            'sum' => 0
                        ];
                    }

                    $statistic[$item['student']['faculty']]['count'] += 1;
                    $statistic[$item['student']['faculty']]['sum'] += $item['average'];
                }
            }
        }

        return $statistic;
    }


    /**
     * Получение статистики по группе
     * @param $year
     * @param $form
     * @param $unit
     * @return array
     */
    public function getGroupStatistic($year, $form, $unit){
        $data = Statistic::find()
            ->select(['student_id', 'average'])
            ->with(['student' => function($query){
                /** @var ActiveQuery $query */
                $query->select(['form', 'id', 'faculty', 'speciality', 'course', 'group']);
            }])
            ->where('year = :year', [':year' => $year])
            ->asArray()
            ->all();

        $statistic = [];
        foreach($data as $item){

            if($item['student'] && (($form == self::ALL_FORMS) || ($form == $item['student']['form']))){

                if($unit == $item['student']['faculty']){

                    $speciality = $item['student']['speciality'];
                    $group = $item['student']['group'];
                    $course = $item['student']['course'];

                    if(!isset($statistic[$speciality])){
                        $statistic[$speciality] = [
                            'title' => Digest::getItemValue(Dictionary::DICTIONARY_SPECIALITY, $speciality),
                            'groups' => [],
                        ];
                    }

                    if(!isset($statistic[$speciality]['groups'][$group])){
                        $statistic[$speciality]['groups'][$group] = [
                            'title' => Digest::getItemValue(Dictionary::DICTIONARY_GROUP, $group),
                            'count' => 0,
                            'sum' => 0,
                            'courses' => []
                        ];
                    }

                    $statistic[$speciality]['groups'][$group]['count'] += 1;
                    $statistic[$speciality]['groups'][$group]['sum'] += $item['average'];

                    if(!isset($statistic[$speciality]['groups'][$group]['courses'][$course])){
                        $statistic[$speciality]['groups'][$group]['courses'][$course] = ['count' => 0, 'sum' => 0];
                        ksort($statistic[$speciality]['groups'][$group]['courses']);
                    }

                    $statistic[$speciality]['groups'][$group]['courses'][$course]['count'] += 1;
                    $statistic[$speciality]['groups'][$group]['courses'][$course]['sum'] += $item['average'];
                }
            }
        }

        return $statistic;
    }
}
