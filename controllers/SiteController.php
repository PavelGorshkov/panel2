<?php
namespace app\controllers;

use app\modules\user\helpers\UserSettings;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions'=>['captcha', 'migrate'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'app\modules\core\components\actions\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     *
     * @throws \yii\base\ExitException
     */
    public function actionIndex()
    {
        $this->redirect(Url::to(app()->getModule('user')->profilePage));

        app()->end();
    }


    /**
     * @param string|null $skin
     */
	 public function actionSkins($skin = null) {

        if ($skin !== null) {

            UserSettings::model()->skinTemplate = $skin;
        }
    }


    public function actionMigrate() {

        app()->migrator->updateToLatestSystem();
    }


    public function actionSidebar($sidebar) {

        if (in_array($sidebar, ['remove', 'add'])) {

            UserSettings::model()->sideBar = $sidebar=='add'?'sidebar-collapse':"s";
        }
    }
}
