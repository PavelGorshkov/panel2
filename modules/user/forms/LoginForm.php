<?php

namespace app\modules\user\forms;

use app\modules\user\helpers\ModuleTrait;
use app\modules\user\helpers\Password;
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

                    if ($confirmationRequired && !$this->user->isConfirmedEmail()) {

                        $this->addError($attribute, 'Вам необходимо подтвердить E-mail адрес');
                    }

                    if ($this->user->getIsBlocked()) {

                        $this->addError($attribute, 'Ваш аккаунт заблокирован');
                    }
                }
            }],
            ['password', function ($attribute) {

                if ($this->user === null && $this->module->isFromLDAP()) {

                    $this->user = app()->userManager->findUserLDAP($this);
                }

                if ($this->user === null || !Password::validate($this->password, $this->user->hash)) {

                    if ($this->module->isFromLDAP()) {

                        $this->user = app()->userManager->findUserById($this);
                    }

                    if ($this->user === null) {
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

            $this->user = app()->userManager->findUserByUsernameOrEmail(trim($this->login));
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