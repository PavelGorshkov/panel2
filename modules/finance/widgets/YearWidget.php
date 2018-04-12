<?php

namespace app\modules\finance\widgets;

use Exception;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\widgets\Menu;

/**
 * Class YearWidget
 * @package app\modules\finance\widgets
 */
class YearWidget extends Widget
{

    public $minYear = null;
    public $current = null;
    public $maxYear = null;

    public $action = null;

    protected $menu;

    /**
     * Initializes the object.
     */
    public function init()
    {
        parent::init();

        if ($this->minYear === null) {
            $this->minYear = date('Y');
        }

        if ($this->maxYear === null) {

            $this->maxYear = date('Y');

            if ((integer)date('n') > 8) {

                $this->maxYear = $this->maxYear + 1;
            }
        }

        if ( $this->current === null
          || $this->current < $this->minYear
          || $this->current > $this->maxYear
        ) {

            $this->current = $this->maxYear;
        }

        if (empty($this->action)) {

            $this->action = app()->controller->action->id;
        }

        $url = ArrayHelper::merge([app()->controller->action->id], app()->request->get(null, []));

        for ($year = $this->minYear; $year <= $this->maxYear; $year++) {

            $this->menu[] = array(
                'label' => $year,
                'url' => ArrayHelper::merge($url, [$this->action => ['year'=>$year]]),
                'active' => $year == $this->current,
            );
        }
    }


    /**
     * @return string
     * @throws Exception
     */
    public function run()
    {
        return Menu::widget([

            'items' => $this->menu,
            'options' => ['class' => 'nav nav-pills'],
            'encodeLabels' => true,
            'id' => 'yearMenu_'.$this->action,
        ]);
    }
}