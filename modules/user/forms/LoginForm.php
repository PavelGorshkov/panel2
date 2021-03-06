<?php

namespace app\modules\user\forms;

use app\modules\user\helpers\EmailConfirmStatusHelper;
use app\modules\user\helpers\ModuleTrait;
use app\modules\user\helpers\Password;
use app\modules\user\helpers\UserStatusHelper;
use app\modules\user\models\IdentityUser;
use yii\base\Model;
use yii\db\Expression;

/**
 * Class LoginForm
 * @package app\modules\user\forms
 */
class LoginForm extends Model
{
    use ModuleTrait;

    public $login;

    public $password;

    /**
     * @var IdentityUser
     */
    protected $user;

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'login' => 'Email или Логин',
            'password' => 'Пароль',
        ];
    }


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            ['login', 'trim'],
            ['login', function ($attribute) {

                if ($this->user !== null) {

                    $confirmationRequired = $this->module->enableConfirmation
                        && !$this->module->enableUnconfirmedLogin;

                    if ($confirmationRequired && !EmailConfirmStatusHelper::isConfirmedEmail($this->user)) {

                        $this->addError($attribute, 'Вам необходимо подтвердить E-mail адрес');
                    }

                    if (UserStatusHelper::isBlocked($this->user)) {

                        $this->addError($attribute, 'Ваш аккаунт заблокирован');
                    }
                }
            }],
            ['password', function ($attribute) {

                if ($this->user === null && $this->module->isFromLDAP()) {

                    if (app()->userManager->isAuthLDAP($this->login, $this->password)) {

                        $this->user = app()->userManager->findUserByLdap($this->login, $this->password);
                    }
                }

                if ($this->user === null) {

                    $this->addError($attribute, 'Неверный логин или пароль');

                } elseif (!Password::validate($this->password, $this->user->hash)) {

                    if (
                        $this->module->isFromLDAP()
                        && app()->userManager->isAuthLDAP($this->login, $this->password)
                    ) {

                        app()->userManager->updateUserHashPassword($this->user, $this->password);
                    } else {

                        $this->addError($attribute, 'Неверный логин или пароль');
                    }
                }
            }],
        ];
    }


    /**
     * @return bool
     */
    public function beforeValidate()
    {

        if (parent::beforeValidate()) {

            $this->user = IdentityUser::findByUsernameOrEmail(trim($this->login));
            return true;
        }

        return false;
    }


    /**
     * @return bool
     */
    public function login()
    {

        $this->user->updateAttributes(
            [
                'visited_at' => new Expression('NOW()'),
                'user_ip' => ip2long(app()->request->getUserIP())
            ]);

        return app()->user->login($this->user, $this->module->sessionLifeTimeDate * 24 * 3600);
    }
}