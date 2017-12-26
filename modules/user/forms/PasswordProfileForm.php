<?php
namespace app\modules\user\forms;

use app\modules\core\components\FormModel;
use app\modules\user\helpers\ModuleTrait;

class PasswordProfileForm extends FormModel {

    use ModuleTrait;

    public $password;

    public $r_password;

    public function rules() {

        return [
            [['password', 'r_password'], 'required'],
            [['password', 'r_password'], 'string', 'min'=>$this->module->minPasswordLength],
            ['r_password', 'compare', 'compareAttribute'=>'password', 'message' => 'Пароли не совпадают'],
            [['password', 'r_password'], 'emptyOnInvalid'],
        ];
    }


    public function emptyOnInvalid($attribute, /** @noinspection PhpUnusedParameterInspection */  $params)
    {
        if ($this->hasErrors()) {

            $this->$attribute = null;
        }
    }


    public function formName() {

        return 'email-profile-form';
    }


    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'r_password' => 'Подтверждение пароля',
        ];
    }
}