<?php
namespace app\modules\user\controllers;

use app\modules\core\components\RedactorController;
use app\modules\user\forms\EmailProfileForm;
use app\modules\user\forms\PasswordProfileForm;
use app\modules\user\forms\ProfileForm;
use app\modules\user\helpers\TokenTypeHelper;
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
        '@index'=>'Панель управления',
        '@view'=>'Профиль',
    ];

    public $layout = '@app/modules/user/views/layouts/profile';

    protected $title = 'Профиль';


    /**
     * @return array
     */
    public function behaviors() {

        return [
            'access' => [
                'class' => AccessControl::className(),
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
     * @return string
     */
    public function actionIndex() {

        return $this->render('index', [
            'admin'=>app()->menuManager->admin,
            'main'=>app()->menuManager->main,
            'redactor'=>app()->menuManager->redactor,
            'dictionary'=>app()->moduleManager->isInstallModule('dictionary') ? app()->dictionary->getMenu():[],
        ]);
    }


    /**
     * @return string
     */
    public function actionView()
    {
        return $this->render('view', [
            'info'=>user()->info,
            'module'=>$this->module,
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

        $this->performAjaxValidation($model);

        if ($model->load(app()->request->post()) && $model->validate()) {

            if (app()->userManager->changeEmail(user()->info, $model->email)) {

                if ($this->module->emailAccountVerification) {

                    user()->setWarningFlash('Вам необходимо продтвердить новый e-mail, проверьте почту!');
                } else {

                    user()->setSuccessFlash('Ваш email был изменен');
                }

                $this->redirect(Url::to(['update']));
                app()->end();
            }
        }

        return $this->render('email', ['model'=>$model]);
    }


    /**
     * @return string
     * @throws \yii\base\ExitException
     * @throws \yii\base\Exception
     */
    public function actionChangePassword()
    {
        $this->layout = '@app/modules/user/views/layouts/profile_box';

        $this->setSmallTitle('Изменение пароля');

        $model = new PasswordProfileForm();

        $this->performAjaxValidation($model);

        if ($model->load(app()->request->post()) && $model->validate()) {

            if (app()->userManager->changePasswordProfile($model->password)) {

                user()->setSuccessFlash('Ваш пароль успешно изменен!');

            } else {

                user()->setErrorFlash('Не удалось изменить пароль');
            }

            $this->redirect(Url::to(['view']));
            app()->end();
        }

        return $this->render('password', ['model'=>$model]);
    }


    /**
     * @return string
     * @throws \yii\base\ExitException
     */
    public function actionUpdate()
    {
        $this->layout = '@app/modules/user/views/layouts/profile_box';

        $this->setSmallTitle('Изменение профиля');

        $model = new ProfileForm();
        $model->setAttributes(user()->info->getAttributes());
        $model->email = user()->info->email;

        $this->performAjaxValidation($model);

        if (
             $model->load(app()->request->post())
          && $model->validate()
          && $model->upload()
        ) {

            if (app()->userManager->saveProfile($model)) {

                user()->setSuccessFlash('Ваши данные обновлены');
            }

            $this->redirect(Url::to(['view']));
            app()->end();
        }

        return $this->render($this->action->id, ['model'=>$model, 'module'=>$this->module]);
    }


    /**
     * @param string $token
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\base\ExitException
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionConfirm($token) {

        if (!$this->module->emailAccountVerification) {

            throw new NotFoundHttpException();
        }

        if (app()->userManager->verifyEmail($token, TokenTypeHelper::EMAIL_VERIFY)) {

            user()->setSuccessFlash('Ваш email подтвержден!');
        } else {

            user()->setErrorFlash('Не удалось подтвердить email');
        }

        $this->redirect(Url::to(['view']));
        app()->end();
    }

}
