<?php

namespace app\modules\user\controllers;

use app\modules\core\components\WebController;
use app\modules\core\helpers\CookieHelper;
use app\modules\user\forms\LoginForm;
use app\modules\user\forms\ProfileRegistrationForm;
use app\modules\user\forms\RecoveryForm;
use app\modules\user\forms\RecoveryPasswordForm;
use app\modules\user\forms\RegistrationForm;
use app\modules\user\helpers\TokenTypeHelper;
use app\modules\user\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
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
    public $layout = "login";

    /**
     * @inheritdoc
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
            'verb' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['POST'],
                ],
            ]
        ];
    }


    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionLogin()
    {
        if (!app()->user->isGuest) return $this->goHome();

        $model = new LoginForm();

        $this->performAjaxValidation($model);

        if (
            $model->load(app()->request->post())
            && $model->validate()
        ) {

            if ($model->login()) return $this->goBack();
        }

        if (!app()->request->isPost) {

            app()->response->cookies->removeAll();
            app()->session->destroy();
        }

        return $this->render('login', [
            'model' => $model,
            'module' => $this->module,
        ]);
    }


    /**
     * @return \yii\web\Response
     */
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
    public function actionRegistration()
    {
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

                return $this->redirect(Url::to([$this->module->loginPage]));
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
     * @return \yii\web\Response
     * @throws \Exception
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     * @throws \Throwable
     */
    public function actionActivation($token)
    {

        if (app()->userManager->verifyEmail($token, TokenTypeHelper::ACTIVATE)) {

            app()->user->setSuccessFlash('Вы успешно активировали учетную запись!');
        } else {

            app()->user->setErrorFlash('Ошибка активации! Возможно указан неверный ключ активации!');
        }

        return $this->redirect(
            !app()->user->isGuest
                ? Url::to(['/user/profile/index'])
                : Url::to($this->module->loginPage)
        );
    }


    /**
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws \yii\base\ExitException
     */
    public function actionRecovery()
    {

        if ($this->module->recoveryDisabled) {
            throw new NotFoundHttpException();
        }

        $model = new RecoveryForm();

        $this->performAjaxValidation($model);

        if ($model->load(app()->request->post()) && $model->validate()) {

            if (app()->userManager->recoverySendMail($model->getUser())) {

                user()->setSuccessFlash('На указанный email отправлено письмо с инструкцией по восстановлению пароля!');
            } else {

                user()->setErrorFlash('При восстановлении пароля произошла ошибка!');
            }

            return $this->redirect(Url::to($this->module->loginPage));
        }

        return $this->render($this->action->id, ['model' => $model]);
    }


    /**
     * @param $token
     * @return string
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \yii\base\ExitException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     * @throws \Throwable
     */
    public function actionRecoveryPassword($token)
    {

        if ($this->module->recoveryDisabled) throw new NotFoundHttpException();

        list($tokenModel, $user) = app()->userManager->getTokenUserList($token, TokenTypeHelper::CHANGE_PASSWORD);

        if ($tokenModel === null || $user === null) throw new NotFoundHttpException();

        if ($this->module->autoRecoveryPassword === Module::CHOICE_YES) {

            if (app()->userManager->generatePassword($user, $tokenModel)) {

                user()->setSuccessFlash('Новый пароль отправлен Вам на email!');
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
            } else {
                user()->setErrorFlash('При изменении пароля произошла ошибка!');
            }

            return $this->redirect(Url::to($this->module->loginPage));
        }

        return $this->render($this->action->id, ['model' => $model, 'module' => $this->module]);
    }
}