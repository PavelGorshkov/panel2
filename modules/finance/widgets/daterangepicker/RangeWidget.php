<?php

namespace app\modules\finance\widgets\daterangepicker;

use app\modules\finance\widgets\daterangepicker\assets\DateRangeAsset;
use kartik\daterange\DateRangePicker;
use yii\web\View;

/**
 * Class RangeWidget
 * @package app\modules\finance\widgets\daterangepicker
 */
class RangeWidget extends DateRangePicker
{
    public $start = null;
    public $finish = null;

    public $year;
    public $action;

    protected $min = null;
    protected $max = null;
    protected $get = [];

    //public $hideInput = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        //$this->get = $_GET;

        $this->get = app()->request->get(null, []);

        if (empty($this->action)) {

            $this->action = app()->controller->action->id;
        }

        if (empty($this->year)) $this->year = date('Y');

        $this->min = $this->year . "-01-01";

        $this->max = $this->year == date('Y')
            ? $this->max = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), $this->year))
            : $this->max = $this->year . "-12-31";
    }


    /**
     * @return void
     */
    public function run()
    {
        DateRangeAsset::register($this->view);

        $id = 'range_'. $this->action;

        echo <<<HTML
        <div style="float: right;">
            <button id="{$id}" class="btn btn-default">
                <i class="fa fa-calendar"></i> 
                Выберите период<i class="fa fa-caret-down"></i>
            </button>        
        </div>
HTML;

        $min_start = $this->min;
        $max_finish = $this->max;
        $start = isset($this->start) ? $this->start : $min_start;
        $finish = isset($this->finish) ? $this->finish : $max_finish;

        $js = <<<JS
!function ($) {
$(function() {

    $("#$id").daterangepicker(
        {
            "showDropdowns": true,
            "linkedCalendars": false,
            "autoUpdateInput": false,
            'format':"YYYY-MM-DD",
            'locale':{
                'format':"YYYY-MM-DD",
                'separator':" - ",
                "applyLabel":"Принять",
                "cancelLabel":"Отмена",
                "fromLabel":"От",
                "toLabel":"До",
                'customRangeLabel':'Выберите',
                "daysOfWeek":[
                    "Вс",
                    "Пн",
                    "Вт",
                    "Cр",
                    "Чт",
                    "Пт",
                    "Сб"],
                "monthNames":[
                    "Январь",
                    "Февраль",
                    "Март",
                    "Апрель",
                    "Май",
                    "Июнь",
                    "Июль",
                    "Август",
                    "Сентябрь",
                    "Октябрь",
                    "Ноябрь",
                    "Декабрь"
                ],
                "firstDay":1
            },
            startDate: moment("{$start}", 'YYYY-MM-DD'),
            endDate: moment("{$finish}", "YYYY-MM-DD"),
            minDate: moment("{$min_start}", 'YYYY-MM-DD'),
            maxDate: moment("{$max_finish}", "YYYY-MM-DD")
        },
        function (begin, end) {

            js = new Url();
                    
            var start = '{$this->action}[start]',
                finish = '{$this->action}[finish]';
            
            console.log(start);
            console.log(finish);

            if (begin.format('')=='Invalid date')
             {
                delete js.query.start;
                delete js.query.finish;
             } else
             {
                console.log(js.query);    
                if (begin < moment("{$min_start}", 'YYYY-MM-DD')) {

                     js.query[start] = moment("{$min_start}", 'YYYY-MM-DD').format('YYYY-MM-DD');
                } else {

                    js.query[start] = begin.format('YYYY-MM-DD');
                }
                if (end > moment("{$max_finish}", 'YYYY-MM-DD')) {

                     js.query[finish] = moment("{$max_finish}", 'YYYY-MM-DD').format('YYYY-MM-DD');
                } else {

                    js.query[finish] =  end.format('YYYY-MM-DD');
                }
            }

            location.href = js.toString();
        }
    );
})
}(window.jQuery)
JS;


        $this->view->registerJs($js, View::POS_END);
    }


}