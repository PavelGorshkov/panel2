<?php

namespace app\modules\user\forms;

use app\modules\core\components\FormModel;
use app\modules\core\interfaces\SaveModelInterface;
use app\modules\user\helpers\ModuleTrait;
use yii\base\Model;

/**
 * Class PasswordProfileForm
 * @package app\modules\user\forms
 */
class PasswordProfileForm extends FormModel implements SaveModelInterface
{

    use ModuleTrait;

    public $password;

    public $r_password;

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
    public function emptyOnInvalid($attribute, /** @noinspection PhpUnusedParameterInspection */
                                   $params)
    {
        if ($this->hasErrors()) {

            $this->$attribute = null;
        }
    }

    /**
     * @return string
     */
    public function formName()
    {

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

    /**
     * Передача данных в $model и обработка данных в переданной модели
     * Например, Передаем данные в ActiveRecord и сохранение данных AR в БД
     *
     * @param Model $model
     * @return boolean
     */
    public function processingData(Model $model)
    {
        printr($model, 1);

        return false;
    }
}