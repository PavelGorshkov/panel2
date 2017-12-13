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

    public function setSmallTitle($title) {

        $this->smallTitle = $title;
    }


    public function setTitle($title) {

        $this->title = $title;
    }


    public function getSmallTitle() {

        return $this->smallTitle;
    }


    public function getTitle() {

        return $this->title;
    }


    public function setBreadcrumbs(array $breadcrumbs) {

        $this->params['breadcrumbs'] = $breadcrumbs;
    }
}