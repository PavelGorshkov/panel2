<?php
namespace app\modules\user\forms;

use app\modules\core\components\FormModel;
use app\modules\user\helpers\ModuleTrait;

/**
 * Class PasswordProfileForm
 * @package app\modules\user\forms
 */
class PasswordProfileForm extends FormModel {

    use ModuleTrait;

    public $password;

    public $r_password;

    /**
     * @return array
     */
    public function rules() {

        return [
            [['password', 'r_password'], 'required'],
            [['password', 'r_password'], 'string', 'min'=>$this->module->minPasswordLength],
            ['r_password', 'compare', 'compareAttribute'=>'password', 'message' => 'Пароли не совпадают'],
            [['password', 'r_password'], 'emptyOnInvalid'],
        ];
    }


    /**
     * @param string $attribute
     * @param array $params
     */
    public function emptyOnInvalid($attribute, /** @noinspection PhpUnusedParameterInspection */  $params)
    {
        if ($this->hasErrors()) {

            $this->$attribute = null;
        }
    }


    /**
     * @return string
     */
    public function formName() {

        return 'email-profile-form';
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'r_password' => 'Подтверждение пароля',
        ];
    }
}