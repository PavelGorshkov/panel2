<?php

namespace app\modules\core\components\actions;

use app\modules\core\interfaces\SaveModelInterface;
use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\widgets\ActiveForm;

/**
 * Class SaveModelAction
 * @package app\modules\core\components\actions
 */
class SaveModelAction extends WebAction
{
    public $modelForm = null;

    /**
     * @var string|null
     */
    public $model = null;

    /**
     * @var bool
     */
    public $isNewRecord = true;

    /**
     * @var bool
     */
    public $isRefresh = false;

    /**
     * @var string
     */
    public $successFlashMessage = 'Save model success!';

    /**
     * @var string|null
     */
    public $errorFlashMessage = null;

    /**
     * @var string|null
     */
    public $successRedirect = null;

    /** @var Model|SaveModelInterface */
    protected $modelFormInstance = null;

    /** @var Model|ActiveRecordInterface */
    protected $modelInstance = null;


    /**
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {

        parent::init();

        if ($this->modelForm === null)
            throw new ServerErrorHttpException('In action ' . $this->id . ' in controller ' . $this->controller->id . ' not found param modelForm');

        $this->modelFormInstance = \Yii::createObject([
            'class' => $this->modelForm,
        ]);

        if ($this->modelFormInstance === null)
            throw new ServerErrorHttpException('In action ' . $this->id . ' in controller  ' . $this->controller->id . ' not found instance class modelForm');


        if (!($this->modelFormInstance instanceof SaveModelInterface))
            throw new ServerErrorHttpException('In action ' . $this->id . ' form model ' . $this->modelForm . ' not implements interface \\app\\modules\\core\\interfaces\\SaveModelInterface');

        $this->modelInstance = \Yii::createObject([

            'class' => $this->model,
        ]);

        if (!($this->modelInstance instanceof Model)) {

            throw new ServerErrorHttpException('In action ' . $this->id . ' model ' . $this->model . ' not instance class \\yii\\base\\Model');
        }

        if (!($this->modelInstance instanceof ActiveRecordInterface)) {

            throw new ServerErrorHttpException('In action ' . $this->id . ' model ' . $this->model . ' not instance interface \\yii\\db\\ActiveRecordInterface');
        }
    }


    /**
     * @param int $id
     *
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\base\ExitException
     */
    public function run($id = 0)
    {

        if (!$this->isNewRecord) {

            $this->modelInstance = $this->findModel($id);

            $this->modelFormInstance->setAttributes($this->modelInstance->getAttributes());
        }

        $this->performAjaxValidation();

        if (
            $this->modelFormInstance->load(app()->request->post())
            && $this->modelFormInstance->validate()
        ) {

            if ($this->modelFormInstance->processingData($this->modelInstance)) {

                user()->setSuccessFlash($this->successFlashMessage);
                return $this->redirectPage();

            } else {

                if ($this->errorFlashMessage !== null) {
                    user()->setErrorFlash($this->errorFlashMessage);
                }
            }

        }

        return $this->render([
            'model' => $this->modelFormInstance,
            'module' => app()->controller->module,
        ]);
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
    public function findModel($id)
    {
        /** @var $modelClass Model|ActiveRecordInterface */
        $modelClass = $this->model;

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


    /**
     * @throws \yii\base\ExitException
     */
    protected function performAjaxValidation()
    {
        if (app()->request->isAjax && $this->modelFormInstance->load(app()->request->post())) {

            app()->response->format = Response::FORMAT_JSON;
            app()->response->data = ActiveForm::validate($this->modelFormInstance);
            app()->response->send();
            app()->end();
        }
    }


    /**
     * @return Response
     */
    protected function redirectPage()
    {

        if ($this->isRefresh) return $this->controller->refresh();

        if ($this->successRedirect !== null) return $this->controller->redirect(Url::to($this->successRedirect));

        $key = $this->modelInstance->primaryKey();
        $query = [];

        foreach ($key as $v) {

            $query[$v] = $this->modelInstance->$v;
        }

        $request = app()->request->post('submit-type', ArrayHelper::merge(['update'], $query));

        return $this->controller->redirect(Url::to($request));
    }
}