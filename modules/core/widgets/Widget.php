<?php
namespace app\modules\core\widgets;

/**
 * Class Widget
 * @package app\modules\core\widgets
 */
abstract class Widget extends \yii\bootstrap\Widget
{

    public $cacheTime = null;

    /**
     * @inheritdoc
     */
    public function init() {

        parent::init();

        if ($this->cacheTime === null) {

            $this->cacheTime = app()->getModule('core')->coreCacheTime;
        }
    }
}