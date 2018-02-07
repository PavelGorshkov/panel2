<?php

namespace app\modules\user\controllers;

use app\modules\core\components\actions\GridViewAction;
use app\modules\core\components\actions\SaveModelAction;
use app\modules\user\models\Access;
use app\modules\user\models\RoleAccess;
use yii\filters\AccessControl;
use app\modules\core\components\WebController;
use yii\filters\VerbFilter;
use app\modules\user\models\Role;
use app\modules\user\models\SearchRole;
use app\modules\user\forms\RoleFormModel;
use app\modules\user\auth\RolesTask;
use yii\web\NotFoundHttpException;

/**
 * RolesController implements the CRUD actions for Role model.
 *
 * Class ManagerController
 * @package app\modules\user\controllers
 */
class RolesController extends WebController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => RolesTask::createRulesController(),
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
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
        $this->setTitle('Группы пользователей');

        return parent::beforeAction($action);
    }


    /**
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => GridViewAction::className(),
                'searchModel' => SearchRole::className(),
                'smallTitle' => 'Список',
            ],
            'create' => [
                'class' => SaveModelAction::className(),
                'modelForm' => RoleFormModel::className(),
                'model' => Role::className(),
                'isNewRecord' => true,
            ],
            'update' => [
                'class' => SaveModelAction::className(),
                'modelForm' => RoleFormModel::className(),
                'model' => Role::className(),
                'isNewRecord' => false,
            ],
        ];
    }


    /**
     * Deletes an existing Role model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     *
     * public function actionDelete()
     * {
     * //TODO реализуйте метод удаления данных
     * //$this->findModel()->delete();
     *
     * //return $this->redirect(['index']);
     * }
     **/


    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionAccess($id)
    {
        $model = $this->findModel($id);

        $operations = app()->buildAuthManager->getListOperations();

        $data = RoleAccess::getData($model->id);

        if (app()->request->isPost) {

            if ($post = app()->request->post('Access', false)) {

                if (RoleAccess::setData($model->id, $post)) {

                    user()->setSuccessFlash('Уровни доступа определены');
                } else {

                    user()->setWarningFlash('Нет данных для обновления! Настройки не обновлены.');
                }
            } else {

                if (RoleAccess::deleteData($model->id)) {

                    user()->setSuccessFlash('Настройки обновлены!');
                }
            }
        }

        return $this->render('access', ['model' => $model,
            'operations' => $operations,
            'data' => $data,]);
    }


    /**
     * @param int $id
     * @return null|Role
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = Role::findOne($id);

        if ($model === null) {

            throw  new NotFoundHttpException('Not found model Role');
        }

        return $model;
    }
}
