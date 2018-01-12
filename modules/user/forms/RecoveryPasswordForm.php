<?php

namespace app\modules\user\forms;

use app\modules\user\helpers\ModuleTrait;
use yii\base\Model;

/**
 * Class RecoveryPasswordForm
 * @package app\modules\user\forms
 */
class RecoveryPasswordForm extends Model
{

    use ModuleTrait;

    public $password;

    public $r_password;

    /**
     * @return string
     */
    public function formName()
    {
        return 'recovery-password-form';
    }

    /**
     * @return array
     */
    public function rules()
    {

        return [
            [['password', 'r_password'], 'required'],
            [['password', 'r_password'], 'string', 'min' => $this->module->minPasswordLength],
            ['r_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],

            [['password', 'r_password'], 'emptyOnInvalid'],
        ];
    }

    /**
     * @param string $attribute
     * @param array $params
     */
    public function emptyOnInvalid($attribute, /** @noinspection PhpUnusedParameterInspection */ $params = null)
    {
        if ($this->hasErrors()) {

            $this->$attribute = null;
        }
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