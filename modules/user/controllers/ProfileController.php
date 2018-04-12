<?php

namespace app\modules\user\controllers;

use app\modules\core\components\actions\SaveModelAction;
use app\modules\core\components\RedactorController;
use app\modules\user\forms\EmailProfileForm;
use app\modules\user\forms\PasswordForm;
use app\modules\user\forms\ProfileForm;
use app\modules\user\helpers\TokenTypeHelper;
use app\modules\user\models\ManagerUser;
use app\modules\user\Module;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Class ProfileController
 * @package app\modules\user\controllers
 *
 * @property Module $module
 */
class ProfileController extends RedactorController
{
    protected $actionMenu = [
        '@index' => 'Панель управления',
        '@view' => 'Профиль',
    ];

    public $layout = '@app/modules/user/views/layouts/profile';

    protected $title = 'Профиль';


    /**
     * @return array
     */
    public function behaviors()
    {

        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'view',
                            'update',
                            'change-password',
                            'email',
                            'confirm',
                            'change-password'
                        ],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    /**
     * @inheritdoc
     * @return array
     */
    public function actions()
    {

        return [
            'change-password' => [
                'class' => SaveModelAction::class,
                'modelForm' => PasswordForm::class,
                'model' => ManagerUser::class,
                'isNewRecord' => true,
                'view' => 'password',
                'successFlashMessage' => 'Ваш пароль успешно изменен!',
                'errorFlashMessage' => 'Не удалось изменить пароль',
                'successRedirect' => ['view'],
            ]
        ];
    }


    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'admin' => app()->menuManager->admin,
            'main' => app()->menuManager->main,
            'redactor' => app()->menuManager->redactor,
            'dictionary' => app()->moduleManager->isInstallModule('dictionary') ? app()->dictionary->getMenu() : [],
        ]);
    }


    /**
     * @return string
     */
    public function actionView()
    {
        return $this->render('view', [
            'info' => user()->identity,
            'profile'=>user()->profile,
            'module' => $this->module,
        ]);
    }


    /**
     * @return string
     * @throws \yii\base\Exception
     * @throws \yii\base\ExitException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionEmail()
    {
        $this->layout = '@app/modules/user/views/layouts/profile_box';

        $this->setSmallTitle('Изменение E-mail');

        $model = new EmailProfileForm();
        $model->setAttributes(user()->identity->getAttributes());

        $this->performAjaxValidation($model);

        if ($model->load(app()->request->post()) && $model->validate()) {

            if (app()->userManager->changeEmail(user()->identity, $model->email)) {

                return $this->redirect(Url::to(['update']));
            }
        }

        return $this->render('email', ['model' => $model]);
    }


    /**
     * @return string
     * @throws \yii\base\ExitException
     * @throws \yii\db\Exception
     */
    public function actionUpdate()
    {
        $this->layout = '@app/modules/user/views/layouts/profile_box';

        $this->setSmallTitle('Изменение профиля');

        $modelForm = new ProfileForm();
        $modelForm->setAttributes(user()->identity->getAttributes());
        $modelForm->setAttributes(user()->profile->getAttributes());
        $modelForm->email = user()->identity->email;

        $this->performAjaxValidation($modelForm);

        if (
            $modelForm->load(app()->request->post())
            && $modelForm->validate()
            && $modelForm->upload()
        ) {

            if (app()->userManager->saveProfile($modelForm)) {

                user()->setSuccessFlash('Ваши данные обновлены');
            }

            $this->redirect(Url::to(['view']));
            app()->end();
        }

        return $this->render($this->action->id, ['model' => $modelForm, 'module' => $this->module]);
    }


    /**
     * @param string $token
     * @return \yii\web\Response
     *
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionConfirm($token)
    {

        if (!$this->module->emailAccountVerification) {

            throw new NotFoundHttpException();
        }

        if (app()->userManager->verifyEmail($token, TokenTypeHelper::EMAIL_VERIFY)) {

            user()->setSuccessFlash('Ваш email подтвержден!');
        } else {

            user()->setErrorFlash('Не удалось подтвердить email');
        }

        return $this->redirect(Url::to(['view']));
    }

}
