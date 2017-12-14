<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 14.12.2017
 * Time: 14:18
 */

namespace app\modules\user\controllers;

use app\modules\core\components\WebController;
use app\modules\user\helpers\EventTrait;
use app\modules\user\models\LoginForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class AccountController extends WebController
{
    public $layout = "@app/modules/user/views/layouts/login";

    const EVENT_BEFORE_LOGIN = 'beforeLogin';
    const EVENT_AFTER_LOGIN = 'afterLogin';
    const EVENT_BEFORE_LOGOUT = 'beforeLogout';
    const EVENT_AFTER_LOGOUT = 'afterLogout';

    use EventTrait;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['login', 'auth'], 'roles' => ['?']],
                    ['allow' => true, 'actions' => ['login', 'auth', 'logout'], 'roles' => ['@']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
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

        if ($model->load(app()->request->post()) && model()->login()) {

            $this->trigger(self::EVENT_AFTER_LOGIN, $event);
            return $this->goBack();
        }

        return $this->render('login', [
            'model'=>$model,
            'module'=>$this->module,
        ]);
    }
}