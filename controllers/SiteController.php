<?php
namespace app\controllers;

use app\modules\core\components\actions\ErrorAction;
use app\modules\user\helpers\EmailConfirmStatusHelper;
use app\modules\user\helpers\Password;
use app\modules\user\helpers\RegisterFromHelper;
use app\modules\user\helpers\UserAccessLevelHelper;
use app\modules\user\helpers\UserSettings;
use app\modules\user\helpers\UserStatusHelper;
use app\modules\user\models\IdentityUser;
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
        return $this->redirect(Url::to(app()->getModule('user')->startPage));
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

        $ldapData = app()->ldap->getProvider('user_ldap')->search()->users()->where('samaccountname', '=', 'belorusov_rv')->find('belorusov_rv');

        $password = 'MukhinKS1990';

        $user = new IdentityUser();
        $user->setAttributes([
            'username' => $ldapData->getAccountName(),
            'email' => $ldapData->getEmail(),
            'email_confirm' => EmailConfirmStatusHelper::EMAIL_CONFIRM_YES,
            'hash' => Password::hash($password),
            'status' => UserStatusHelper::STATUS_ACTIVE,
            'registered_from' => RegisterFromHelper::LDAP,
            'access_level' => UserAccessLevelHelper::LEVEL_LDAP,
            'full_name' => $ldapData->getCommonName(),
            'about' => $ldapData->getDepartment(),
            'phone' => $ldapData->getTelephoneNumber() !== null ? $ldapData->getTelephoneNumber() : null,
        ]);

        printr($user, 1);
    }
}
