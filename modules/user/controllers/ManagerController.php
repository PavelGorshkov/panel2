<?php

namespace app\modules\user\controllers;

use app\modules\core\components\actions\DeleteAction;
use app\modules\core\components\actions\GridViewAction;
use app\modules\core\components\actions\SaveModelAction;
use app\modules\core\components\WebController;
use app\modules\user\auth\ManagerTask;
use app\modules\user\forms\PasswordForm;
use app\modules\user\forms\UserFormModel;
use app\modules\user\helpers\UserStatusHelper;
use app\modules\user\models\Access;
use app\modules\user\models\Role;
use app\modules\user\models\RoleAccess;
use app\modules\user\models\SearchUser;
use app\modules\user\models\ManagerUser;
use kartik\grid\EditableColumnAction;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class ManagerController
 * @package app\modules\user\controllers
 */
class ManagerController extends WebController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => ArrayHelper::merge(
                    ManagerTask::createRulesController(),
                    [
                        [
                            'allow' => true,
                            'actions' => ['access-level', 'status'],
                            'roles' => [ManagerTask::OPERATION_UPDATE],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['password'],
                            'roles' => [ManagerTask::OPERATION_UPDATE],
                        ],
                    ])
            ],
        ];
    }


    /**
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => GridViewAction::class,
                'searchModel' => SearchUser::class,
                'smallTitle' => 'Список',
            ],
            'create' => [
                'class' => SaveModelAction::class,
                'modelForm' => UserFormModel::class,
                'model' => ManagerUser::class,
                'isNewRecord' => true,
            ],
            'update' => [
                'class' => SaveModelAction::class,
                'modelForm' => UserFormModel::class,
                'model' => ManagerUser::class,
                'isNewRecord' => false,
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => ManagerUser::class,
                'successMessage' => 'Пользователь успешно удален!',
            ],
            'access-level' => [
                'class' => EditableColumnAction::class,
                'modelClass' => ManagerUser::class,
                'outputValue' => function (ManagerUser $model) {

                    return $model->getAccessGroup();
                },
            ],
            'status' => [
                'class' => EditableColumnAction::class,
                'modelClass' => ManagerUser::class,
                'outputValue' => function ($model, $attribute) {

                    return UserStatusHelper::getValue($model->$attribute, true);
                },
            ],
            'password' => [
                'class' => SaveModelAction::class,
                'modelForm' => PasswordForm::class,
                'model' => ManagerUser::class,
                'isNewRecord' => false,
                'view' => 'password',
                'successFlashMessage' => 'Пароль успешно изменен!',
                'errorFlashMessage' => 'Не удалось изменить пароль',
                'successRedirect' => ['index'],
            ]
        ];
    }


    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->setTitle('Управление пользователями');

        return parent::beforeAction($action);
    }


    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionAccess($id)
    {
        $model = $this->findModel($id);

        if (!$model->isAccessRoles()) {

            throw new ServerErrorHttpException('500', 'Нельзя редактировать системную роль!');
        }

        $operations = app()->buildAuthManager->getListOperations();

        if ($model->isUFAccessLevel()) {

            $role = Role::findOne($model->access_level);
            $dataRoles = RoleAccess::getData($role->id);

        } else {

            $role = null;
            $dataRoles = [];
        }

        $data = Access::getData($model->id);

        if (app()->request->isPost) {

            if ($post = app()->request->post('Access', false)) {

                if (Access::setData($model->id, $post)) {

                    user()->setSuccessFlash('Уровни доступа определены');
                } else {

                    user()->setWarningFlash('Нет данных для обновления! Настройки не обновлены.');
                }
            } else {

                if (Access::deleteData($model->id)) {

                    user()->setSuccessFlash('Настройки обновлены!');
                }
            }

            app()->authManager->flush();

            return $this->redirect((array)app()->request->post(
                'submit-type',
                ['access', 'id' => $model->id]
            ));
        }

        return $this->render('access', [
            'model' => $model,
            'operations' => $operations,
            'data' => $data,
            'role' => $role,
            'dataRoles' => $dataRoles
        ]);
    }


    /**
     * @param int $id
     * @return null|ManagerUser
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = ManagerUser::findOne($id);

        if ($model === null) {

            throw  new NotFoundHttpException('Not found model Role');
        }

        return $model;
    }
}