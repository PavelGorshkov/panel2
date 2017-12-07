<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 07.12.2017
 * Time: 15:07
 */

namespace app\modules\core\components;

/**
 * Class Controller
 * @package app\modules\core\components
 *
 * @property View $view
 */
class WebController extends \yii\web\Controller {

    protected function setTitle($title) {

        $this->view->title = $title;
    }


    protected function setSmallTitle($title) {

        $this->view->smallTitle = $title;
    }
}