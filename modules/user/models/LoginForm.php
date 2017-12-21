<?php
namespace app\modules\user\models;

use app\modules\user\helpers\ModuleTrait;
use app\modules\user\helpers\Password;
use yii\base\Model;

class LoginForm extends Model {

    use ModuleTrait;

    public $login;

    public $password;

    public $rememberMe = false;

    /**
     * @var User
     */
    protected $user;

    public function attributeLabels()
    {
        return [
            'login'      => 'Email или Логин',
            'password'   => 'Пароль',
        ];
    }


    public function rules() {

        return [
            [['login', 'password'], 'required'],
            ['login', 'trim'],
            ['login', function($attribute) {

                if ($this->user !== null){

                    $confirmationRequired = $this->module->enableConfirmation
                        && !$this->module->enableUnconfirmedLogin;

                    if ($confirmationRequired && !$this->user->getIsConfirmed()) {

                        $this->addError($attribute, 'Вам необходимо подтвердить E-mail адрес');
                    }

                    if ($this->user->getIsBlocked()) {

                        $this->addError($attribute, 'Ваш аккаунт заблокирован');
                    }
                }
            }],
            [
                'password', function ($attribute) {

                    if ($this->user === null || !Password::validate($this->password, $this->user->hash)) {

                        $this->addError($attribute, 'Неверный логин или пароль');
                    }
                }
            ],
        ];
    }


    public function beforeValidate() {

        if (parent::beforeValidate()) {

            $this->user = app()->userManager->findUserByUsernameOrEmail(trim($this->login));
            return true;
        }

        return false;
    }


    public function login() {

        if ($this->validate()) {

            $this->user->updateAttributes(
                [
                    'visited_at'=> time(),
                    'user_ip' => app()->request->getUserIP()
                ]);

            return app()->user->login($this->user, $this->module->sessionLifeTimeDate * 24* 3600);
        }
    }
}