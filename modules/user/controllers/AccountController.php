<?php
namespace app\modules\user\controllers;

use app\modules\core\components\WebController;
use app\modules\user\helpers\EventTrait;
use app\modules\user\models\LoginForm;
use app\modules\user\models\ProfileRegistrationForm;
use app\modules\user\Module;
use app\modules\user\models\RegistrationForm;
use yii\filters\AccessControl;

/**
 * Class AccountController
 * @package app\modules\user\controllers
 *
 * @property Module $module
 */
class AccountController extends WebController
{
    public $layout = "@app/modules/user/views/layouts/login";

    const EVENT_BEFORE_LOGIN = 'beforeLogin';
    const EVENT_AFTER_LOGIN = 'afterLogin';
    const EVENT_BEFORE_LOGOUT = 'beforeLogout';
    const EVENT_AFTER_LOGOUT = 'afterLogout';
    const EVENT_BEFORE_REGISTRATION = 'beforeRegistration';
    const EVENT_AFTER_REGISTRATION = 'afterRegistration';

    use EventTrait;

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
                            'registration'
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
        $event = $this->getFormEvent($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_LOGIN, $event);

        if (
            $model->load(app()->request->post())
        &&  $model->login()
        ) {

            $this->trigger(self::EVENT_AFTER_LOGIN, $event);
            return $this->goBack();
        }

        return $this->render('login', [
            'model'=>$model,
            'module'=>$this->module,
        ]);
    }


    public function actionLogout()
    {
        $event = $this->getUserEvent(user()->identity);

        $this->trigger(self::EVENT_BEFORE_LOGOUT, $event);

        user()->logout();

        $this->trigger(self::EVENT_AFTER_LOGOUT, $event);

        return $this->goHome();
    }


    public function actionRegistration() {

        if ($this->module->recoveryDisabled) {
            throw new NotFoundHttpException();
        }

        $model = new RegistrationForm();
        $profile = new ProfileRegistrationForm();

        $event = $this->getFormEvent($model);

        $this->trigger(self::EVENT_BEFORE_REGISTRATION, $event);

        $this->performAjaxValidationMultiply([$profile, $model]);

        if ($model->load(app()->request->post())
         && $profile->load(app()->request->post())) {

            if (
                $model->validate()
             && $profile->validate()
             && app()->userManager->register($model, $profile)
            ) {

                $this->trigger(self::EVENT_AFTER_REGISTRATION, $event);
            }

            printr($model);
            printr($profile, 1);
        }

        return $this->render('registration', [
            'model' => $model,
            'profile' => $profile,
            'module' => $this->module,
        ]);
    }
}