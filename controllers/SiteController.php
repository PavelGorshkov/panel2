<?php
namespace app\controllers;

use Adldap\Models\User;
use app\modules\user\helpers\UserSettings;
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
                'class' => AccessControl::className(),
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

        $path = \Yii::getAlias('@app').'/yii';

        printr(system($path.' cron/test/test'), 1);
        printr($_SERVER, 1);

       // Yii::trace('Test message', 'bitrix');

        printr(app()->userManager->findUserByLdap('gorshkov_pv', '..,djnb)'), 1);

        var_dump(app()->ldap->getProvider('user')->auth()->attempt('gorshkov_pv', '..,djnb)'));

        /* @var $user User*/
        $user = app()->ldap->getProvider('user')->search()->users()->in('OU=staff,OU=MarSU,DC=ad,DC=marsu,DC=ru')->find('gorshkov_pv');

        printr($user->getDepartment());
        printr($user->getCommonName());
        printr($user->getAccountName());
        printr($user->getTelephoneNumber());
        printr($user->getEmail());
        printr($user, 1);
    }
}
