<?php

namespace app\modules\core\components\actions;
use yii\db\ActiveRecordInterface;
use yii\helpers\Url;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class DeleteAction
 * @package app\modules\core\components\actions
 */
class DeleteAction extends WebAction
{
    public $modelClass;

    public $redirect = ['index'];

    public $successMessage = 'Модель успешно удалена';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->modelClass)) {

            throw new ServerErrorHttpException('In action ' . $this->id . ' in controller ' . $this->controller->id . ' not found param modelClass');
        }

        $model = \Yii::createObject([
            'class' => $this->modelClass,
        ]);

        if (empty($model)) {

            throw new ServerErrorHttpException('In action ' . $this->id . ' in controller  ' . $this->controller->id . ' not found instance class modelClass');
        }

        if (!($model instanceof ActiveRecordInterface)) {

            throw new ServerErrorHttpException('In action ' . $this->id . ' model ' . $this->modelClass . ' not instance interface \\yii\\db\\ActiveRecordInterface');
        }

        if (!app()->request->isPost) {

            throw new MethodNotAllowedHttpException();
        }
    }


    /**
     * @param int $id
     * @return mixed|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function run($id)
    {
        $model = $this->findModel($id);

        $model->delete();

        if (app()->request->isAjax) {

            return app()->controller->run(Url::to($this->redirect));
        } else {

            user()->setSuccessFlash($this->successMessage);
            return app()->controller->redirect($this->redirect);
        }
    }


    /**
     * Returns the data model based on the primary key given.
     * If the data model is not found, a 404 HTTP exception will be raised.
     * @param string $id the ID of the model to be loaded. If the model has a composite primary key,
     * the ID must be a string of the primary key values separated by commas.
     * The order of the primary key values should follow that returned by the `primaryKey()` method
     * of the model.
     * @return ActiveRecordInterface the model found
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        /** @var $modelClass ActiveRecordInterface */
        $modelClass = $this->modelClass;

        $keys = $modelClass::primaryKey();

        if (count($keys) > 1) {

            $values = explode(',', $id);

            if (count($keys) === count($values)) {
                $model = $modelClass::findOne(array_combine($keys, $values));
            }

        } elseif ($id !== null) {

            $model = $modelClass::findOne($id);
        }

        if (isset($model)) return $model;

        throw new NotFoundHttpException("Object not found: $id");
    }
}