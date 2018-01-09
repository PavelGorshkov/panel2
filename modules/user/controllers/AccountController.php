<?php
namespace app\modules\user\controllers;

use app\modules\core\components\WebController;
use app\modules\user\forms\LoginForm;
use app\modules\user\forms\ProfileRegistrationForm;
use app\modules\user\forms\RecoveryForm;
use app\modules\user\forms\RecoveryPasswordForm;
use app\modules\user\forms\RegistrationForm;
use app\modules\user\helpers\TokenTypeHelper;
use app\modules\user\Module;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Class AccountController
 * @package app\modules\user\controllers
 *
 * @property Module $module
 */
class AccountController extends WebController
{
    public $layout = "@app/modules/user/views/layouts/login";

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'login',
                            'auth',
                            'registration',
                            'activation',
                            'recovery',
                            'recovery-password',
                            'test'
                        ],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }


    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionLogin()
    {
        if (!app()->user->isGuest) $this->goHome();

        $model = new LoginForm();

        $this->performAjaxValidation($model);

        if (
            $model->load(app()->request->post())
         && $model->validate()
        ) {

            if ($model->login())  return $this->goBack();
        }

        return $this->render('login', [
            'model'=>$model,
            'module'=>$this->module,
        ]);
    }


    public function actionLogout()
    {
        user()->logout();

        return $this->goHome();
    }


    /**
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     * @throws \yii\base\ExitException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionRegistration() {

        if ($this->module->registrationDisabled) {
            throw new NotFoundHttpException();
        }

        $model = new RegistrationForm();
        $profile = new ProfileRegistrationForm();

        $this->performAjaxValidationMultiply([$profile, $model]);

        if (
             $model->load(app()->request->post())
          && $profile->load(app()->request->post())
          && $model->validate()
          && $profile->validate()
        ) {

            if (app()->userManager->registerForm($model, $profile)) {

                user()->setSuccessFlash('Учетная запись создана! Проверьте вашу электронную почту');

                $this->redirect(Url::to([$this->module->loginPage]));
                app()->end();
            }
        }


        return $this->render('registration', [
            'model' => $model,
            'profile' => $profile,
            'module' => $this->module,
        ]);
    }


    /**
     * @param $token
     * @throws \Exception
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     * @throws \Throwable
     */
    public function actionActivation($token) {

        if (app()->userManager->verifyEmail($token, TokenTypeHelper::ACTIVATE)) {

            app()->user->setSuccessFlash('Вы успешно активировали учетную запись!');
        } else {

            app()->user->setErrorFlash('Ошибка активации! Возможно указан неверный ключ активации!');
        }

        $this->redirect(
            !app()->user->isGuest
                ?Url::to(['/user/profile/index'])
                :Url::to($this->module->loginPage)
        );
    }


    /**
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     * @throws \yii\base\ExitException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionRecovery() {

        if ($this->module->recoveryDisabled) {
            throw new NotFoundHttpException();
        }

        $model = new RecoveryForm();

        $this->performAjaxValidation($model);

        if ($model->load(app()->request->post()) && $model->validate()) {

            if (app()->userManager->recoverySendMail($model->getUser())) {

                user()->setSuccessFlash('На указанный email отправлено письмо с инструкцией по восстановлению пароля!');
            } else
            {

                user()->setErrorFlash('При восстановлении пароля произошла ошибка!');
            }

            $this->redirect(Url::to($this->module->loginPage));
            app()->end();
        }

        return $this->render($this->action->id, ['model'=>$model]);
    }


    /**
     * @param $token
     * @return string
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\base\ExitException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionRecoveryPassword($token) {

        if ($this->module->recoveryDisabled) throw new NotFoundHttpException();

        list($tokenModel, $user) = app()->userManager->getTokenUserList($token, TokenTypeHelper::CHANGE_PASSWORD);

        if ($tokenModel === null || $user === null) throw new NotFoundHttpException();

        if ($this->module->autoRecoveryPassword === Module::CHOICE_YES) {

            if (app()->userManager->generatePassword($user, $tokenModel)) {

                user()->setSuccessFlash( 'Новый пароль отправлен Вам на email!');
                $this->redirect(Url::to($this->module->loginPage));

            } else {

                user()->setErrorFlash('Ошибка при смене пароля!');
                $this->redirect(Url::to($this->module->recoveryPage));
            }

            app()->end();
        }

        $model = new RecoveryPasswordForm();

        $this->performAjaxValidation($model);

        if ($model->load(app()->request->post()) && $model->validate()) {

            if (app()->userManager->changePassword($user, $tokenModel, $model->password)) {

                user()->setSuccessFlash('Ваш пароль успешно изменен!');
            } else
            {
                user()->setErrorFlash('При изменении пароля произошла ошибка!');
            }

            $this->redirect(Url::to($this->module->loginPage));
            app()->end();
        }

        return $this->render($this->action->id, ['model'=>$model, 'module'=>$this->module]);
    }


    public function actionTest() {


    }
}