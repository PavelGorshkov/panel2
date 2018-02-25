<?php
namespace app\controllers;

use Adldap\Models\User;
use app\modules\core\components\actions\ErrorAction;
use app\modules\user\helpers\UserSettings;
use yii\captcha\CaptchaAction;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'except'=>['migrate'],
                'rules' => [
                    [
                        'actions'=>['captcha', 'test'],
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
                'class' => ErrorAction::class,
            ],
            'captcha' => [
                'class' => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    /**
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        return $this->redirect(Url::to(app()->getModule('user')->profilePage));
    }


    /**
     * @param string|null $skin
     */
	 public function actionSkins($skin = null) {

        if ($skin !== null) {

            UserSettings::model()->skinTemplate = $skin;
        }
    }


    /**
     * @throws \yii\base\Exception
     */
    public function actionMigrate() {

        app()->migrator->updateToLatestSystem();

        return $this->render('@app/modules/core/views/module/migrate', ['message'=> app()->migrator->getHtml()]);
    }


    /**
     * @param $sidebar
     */
    public function actionSidebar($sidebar) {

        if (in_array($sidebar, ['remove', 'add'])) {

            UserSettings::model()->sideBar = $sidebar=='add'?'sidebar-collapse':"s";
        }
    }


    /**
     * @throws \Adldap\AdldapException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\ServerErrorHttpException
     */
    public function actionTest() {

        printr($_SERVER, 1);

       // Yii::trace('Test message', 'bitrix');

        printr(app()->userManager->findUserByLdap('gorshkov_pv', '..,djnb)'), 1);

        var_dump(app()->ldap->getProvider('user')->auth()->attempt('gorshkov_pv', '..,djnb)'));

        /* @var $user User*/
        $user = app()->ldap->getProvider('user')->search()->users()->in('OU=staff,OU=MarSU,DC=ad,DC=marsu,DC=ru')->find('gorshkov_pv');

        printr($user, 1);
    }
}
