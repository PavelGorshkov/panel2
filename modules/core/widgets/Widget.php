<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 07.12.2017
 * Time: 15:43
 */

namespace app\modules\core\widgets;

abstract class Widget extends \yii\bootstrap\Widget
{

    public $cacheTime = null;

    public function init() {

        parent::init();

        if ($this->cacheTime === null) {

            $this->cacheTime = app()->getModule('core')->coreCacheTime;
        }
    }
}