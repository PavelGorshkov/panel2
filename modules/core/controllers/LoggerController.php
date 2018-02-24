<?php
namespace app\modules\core\controllers;

use app\modules\core\auth\LoggerTask;
use app\modules\core\components\WebController;
use app\modules\core\helpers\LoggerHelper;
use app\modules\core\models\LogDataFormModel;
use app\modules\core\models\LogSourceFormModel;
use yii\base\Exception;
use yii\filters\AccessControl;

/**
 * Class LoggerController
 * @package app\modules\core\controllers
 */
class LoggerController extends WebController
{

    /**
     * @return array accessData
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => LoggerTask::createRulesController(),
            ],
        ];
    }

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {

        $this->setTitle('Обзор лог-данных');

        return parent::beforeAction($action);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $this->setSmallTitle("Список");
        $model = new LogSourceFormModel(['scenario' => 'search']);
        $dataProvider = $model->search(app()->request->get());
        return $this->render($this->action->id, ['dataProvider' => $dataProvider, 'model' => $model]);
    }


    /**
     * @throws Exception
     */
    public function actionView()
    {
        $this->setSmallTitle("Подробный просмотр");
        $model = new LogDataFormModel(['scenario' => 'search']);
        $dataProvider = $model->search(app()->request->get());
        return $this->render($this->action->id, ['dataProvider' => $dataProvider, 'model' => $model]);
    }

    /**
     * @param $id
     */
    public function actionDelete($id)
    {
        $source = LoggerHelper::model()->getData();
        foreach ($source as $log) {
            if (isset($log[$id])) {
                unlink($log[$id]['filePath']);
                user()->setSuccessFlash("Элемент успешно удален");
                $this->redirect("index");
            } else {
                user()->setErrorFlash("Невозможно удалить элемент " . $id);
                $this->redirect("index");
            }
        }
    }
}