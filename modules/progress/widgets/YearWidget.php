<?php

namespace app\modules\progress\widgets;

use yii\base\Widget;
use yii\helpers\Url;
use yii\widgets\Menu;

/**
 * Class YearWidget
 * @package app\modules\progress\widgets
 */
class YearWidget extends Widget{

    public $minYear = null;
    public $current = null;
    public $maxYear = null;

    protected $menu;

    /**
     * Initializes the object.
     */
    public function init(){
        parent::init();

        if($this->minYear === null){
            $this->minYear = date('Y');
        }

        if ($this->maxYear === null){
            $this->maxYear = date('Y');
            if((integer)date('n') > 8){
                $this->maxYear = $this->maxYear + 1;
            }
        }

        if(($this->current === null) || ($this->current < $this->minYear) || ($this->current > $this->maxYear) ){
            $this->current = $this->maxYear;
        }

        for ($year = $this->minYear; $year <= $this->maxYear; $year++) {
            $this->menu[] = array(
                'label'=>$year,
                'url'=>Url::to([app()->controller->action->id, 'year' => $year]),
                'active'=> $year == $this->current,
            );
        }
    }


    /**
     * @return string|void
     * @throws \Exception
     */
    public function run(){
        echo Menu::widget([
            'items'=>$this->menu,
            'options'=>['class'=>'nav nav-pills'],
            'encodeLabels'=>true,
            'id'=>'yearMenu',
        ]);
    }
}