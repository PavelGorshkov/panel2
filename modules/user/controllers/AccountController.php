<?php
namespace app\modules\user\controllers;

use app\modules\core\components\WebController;
use app\modules\user\components\TokenStorage;
use app\modules\user\models\LoginForm;
use app\modules\user\models\ProfileRegistrationForm;
use app\modules\user\models\User;
use app\modules\user\Module;
use app\modules\user\models\RegistrationForm;
use yii\filters\AccessControl;
use yii\helpers\Url;

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


    public function actionLogin()
    {
        if (!app()->user->isGuest) $this->goHome();

        $model = new LoginForm();

        $this->performAjaxValidation($model);


        if (
            $model->load(app()->request->post())
        &&  $model->login()
        ) {

            return $this->goBack();
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


    public function actionRegistration() {

        if ($this->module->recoveryDisabled) {
            throw new NotFoundHttpException();
        }

        $model = new RegistrationForm();
        $profile = new ProfileRegistrationForm();

        $this->performAjaxValidationMultiply([$profile, $model]);

        if ($model->load(app()->request->post())
         && $profile->load(app()->request->post())) {

            if (
                $model->validate()
             && $profile->validate()
             && app()->userManager->register($model, $profile)
            ) {

                user()->setSuccessFlash('Учетная запись создана! Проверьте вашу электронную почту');

                $this->redirect(Url::to(['login']));
            }
        }

        return $this->render('registration', [
            'model' => $model,
            'profile' => $profile,
            'module' => $this->module,
        ]);
    }


    public function actionTest() {

        $user = User::findOne(['id'=>1]);

        $tokenStorage = new TokenStorage();


        $tokenStorage->init();

        $tokenStorage->createAccountActivationToken($user);

        printr($tokenStorage, 1);

        /*
        $mailer = app()->mailer;
        $mailer->viewPath = '@app/modules/user/views/mail';

        $mailer->getView()->theme = app()->view->theme;
        $mailer->getView()->title = 'Регистрация на сайте "'.app()->name.'"';

        return $mailer->compose(['html'=>'welcome','text'=>'text/welcome'], ['fullName'=>'Горшков П.В.', 'login'=>'user_login', 'email'=>'test@test.loc'])
            ->setTo('test@test.loc')
            ->setFrom(app()->params['email'])
            ->setSubject($mailer->getView()->title)
            ->send();
        */
    }
}