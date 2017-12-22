<?php
namespace app\modules\user\models;


use app\modules\user\helpers\ModuleTrait;
use yii\base\Model;


class RecoveryPasswordForm extends Model {

    use ModuleTrait;

    public $password;

    public $r_password;

    public function formName()
    {
        return 'recovery-password-form';
    }

    public function rules() {

        return [
            [['password', 'r_password'], 'required'],
            [['password', 'r_password'], 'string', 'min' => $this->module->minPasswordLength],
            ['r_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],

            [['password', 'r_password'], 'emptyOnInvalid'],
        ];
    }

    public function emptyOnInvalid($attribute, /** @noinspection PhpUnusedParameterInspection */ $params)
    {
        if ($this->hasErrors()) {

            $this->$attribute = null;
        }
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'r_password' => 'Подтверждение пароля',
        ];
    }
}