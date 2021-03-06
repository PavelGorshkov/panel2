<?php
namespace app\modules\developer\controllers\actions;

use app\modules\core\components\actions\WebAction;
use app\modules\developer\interfaces\GenerateFileModuleInterface;
use yii\base\Model;
use yii\web\ServerErrorHttpException;

/**
 * Class createItemModuleAction
 * @package app\modules\developer\controllers\actions
 */
class createItemModuleAction extends WebAction  {

    public $model;

    public $successMessage = 'Данные обновлены';

    /**
     * @inheritdoc
     */
    public function init() {

        parent::init();
    }


    /**
     * @param string $module
     * @return string|\yii\web\Response
     * @throws ServerErrorHttpException
     */
    public function run($module = '') {

        /* @var Model|GenerateFileModuleInterface $model */
        $model = new $this->model;

        if (!($model instanceof GenerateFileModuleInterface)) {

            throw new ServerErrorHttpException('Модель "'.$this->model.'" должна реализовать интерфейс "app\modules\developer\models\GenerateFileModuleInterface"');
        }

        if ($module) {

            $model->setModule($module);
        }

        app()->controller->performAjaxValidation($model);

        if ($model->load(app()->request->post()) && $model->validate()) {

            if ($model->generate()) {

                user()->setSuccessFlash($model->getSuccessMessage());

                return $this->controller->redirect(app()->request->referrer);
            }
        }

        return $this->controller->render($this->view, ['model'=>$model]);
    }
}