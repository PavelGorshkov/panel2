<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 07.12.2017
 * Time: 15:07
 */

namespace app\modules\core\components;


use yii\base\Model;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class Controller
 * @package app\modules\core\components
 *
 * @property View $view
 * $property Module $module
 */
class WebController extends \yii\web\Controller {

    public function setTitle($title) {

        $this->view->title = $title;
    }


    public function setSmallTitle($title) {

        $this->view->smallTitle = $title;
    }


    protected function performAjaxValidation(Model $model)
    {
        if (app()->request->isAjax && $model->load(app()->request->post())) {

            app()->response->format = Response::FORMAT_JSON;
            app()->response->data   = ActiveForm::validate($model);
            app()->response->send();
            app()->end();
        }
    }
}