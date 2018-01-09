<?php
namespace app\modules\user\forms;

use app\modules\user\helpers\ModuleTrait;
use app\modules\user\models\User;
use yii\base\Model;
use yii\captcha\Captcha;
use yii\helpers\HtmlPurifier;

class RegistrationForm extends Model
{
    use ModuleTrait;

    public $username;

    public $email;

    public $password;

    public $r_password;

    public $verifyCode;


    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function isCaptchaEnabled()
    {
        if (!$this->module->showCaptcha || !Captcha::checkRequirements()) {
            return false;
        }

        return true;
    }


    public function formName() {

        return 'registration-form';
    }


    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function rules()
    {

        return [
            [['username'], 'filter', 'filter' => 'trim',],
            [
                ['username'], 'filter',
                'filter' => function ($html) {
                    return HtmlPurifier::process(
                        $html,
                        ['Attr.AllowedFrameTargets' => ['_blank'],]
                    );
                }
            ],
            [['username', 'email', 'password', 'r_password'], 'required'],
            ['username', 'unique', 'targetClass' => User::className(), 'message' => 'Имя пользователя уже занято'],
            [
                'username',
                'match',
                'pattern' => '/^[A-Za-z0-9_-]{2,50}$/',
                'message' => 'Неверный формат поля "{attribute}" допустимы только буквы и цифры, от 2 до 20 символов'
            ],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => 'Email уже занят'],
            [['password', 'r_password'], 'string', 'min' => $this->module->minPasswordLength],
            ['r_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
            ['verifyCode', 'captcha', 'skipOnEmpty' => !$this->isCaptchaEnabled()],
            [['verifyCode', 'password', 'r_password'], 'emptyOnInvalid'],
        ];
    }


    public function beforeValidate()
    {
        if ($this->module->generateUserName) {

            $this->username = 'user' . time();
        }

        return parent::beforeValidate();
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'r_password' => 'Подтверждение пароля',
            'verifyCode' => 'Код проверки',
        ];
    }

    public function emptyOnInvalid($attribute, /** @noinspection PhpUnusedParameterInspection */  $params)
    {
        if ($this->hasErrors()) {

            $this->$attribute = null;
        }
    }
}