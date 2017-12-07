<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 07.12.2017
 * Time: 15:01
 */

namespace app\modules\core\components;

use yii\web\View as BaseView;

class View extends BaseView
{
    public $smallTitle = null;


    public function getSmallTitle() {

        return $this->smallTitle;
    }


    public function getTitle() {

        return $this->title;
    }
}