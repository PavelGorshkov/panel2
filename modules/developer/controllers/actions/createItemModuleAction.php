<?php
namespace app\modules\developer\controllers\actions;


use app\modules\core\components\actions\WebAction;
use app\modules\developer\models\GenerateFileModuleInterface;
use yii\base\Model;
use yii\web\ServerErrorHttpException;


class createItemModuleAction extends WebAction  {

    public $model;

    public $successMessage = 'Данные обновлены';

    public function init() {

        parent::init();
    }


    public function run($module = '') {

        /* @var Model|GenerateFileModuleInterface $model */
        $model = new $this->model;

        if (!($model instanceof GenerateFileModuleInterface)) {

            throw new ServerErrorHttpException('Модель "'.$model.'" должна реализовать интерфейс "app\modules\developer\models\GenerateFileModuleInterface"');
        }

        if ($module) {

            $model->setModule($module);
        }

        app()->controller->performAjaxValidation($model);

        if (
            $model->load(app()->request->post())
         && $model->validate()
         && $model->generate()
        ) {

            user()->setSuccessFlash($model->getSuccessMessage());

            return $this->controller->redirect(app()->request->referrer);
        }

        return $this->controller->render($this->view, ['model'=>$model]);
    }
}