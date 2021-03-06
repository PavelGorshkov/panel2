<?php
namespace app\modules\core\components;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Базовй контроллер для работы в приложении
 *
 * Class Controller
 * @package app\modules\core\components
 *
 * @property View $view
 * $property Module $module
 */
class WebController extends Controller
{
    /**
     * Установить заголовок 1 уровня
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->view->title = $title;
    }

    /**
     * Установить заголовок 2 уровня
     *
     * @param string $title
     */
    public function setSmallTitle($title)
    {
        $this->view->smallTitle = $title;
    }


    /**
     * Проверить ajax валидность данных модели формы
     *
     * @param Model $model
     * @throws \yii\base\ExitException
     */
    public function performAjaxValidation(Model $model)
    {
         if (
            app()->request->isAjax && $model->load(app()->request->post())
         ) {

            app()->response->format = Response::FORMAT_JSON;
            app()->response->data = ActiveForm::validate($model);
            app()->response->send();

            app()->end();
        }
    }


    /**
     * Проверить ajax валидность данных e нескольких моделей формы
     *
     * @param Model[] $models
     * @throws \yii\base\ExitException
     */
    public function performAjaxValidationMultiply(array $models)
    {
        if (!app()->request->isAjax) return;

        $load = false;
        $post = app()->request->post();

        /* @var Model $model */
        foreach ($models as &$model) {

            $loadModel = $model->load($post);
            $load = $load || $loadModel;
        }

        if ($load) {

            $data = [];
            foreach ($models as $m) {

                $data = ArrayHelper::merge($data, ActiveForm::validate($m));
            }

            app()->response->format = Response::FORMAT_JSON;
            app()->response->data = $data;
            app()->response->send();
            app()->end();
        }
    }
}