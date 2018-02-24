<?php

namespace app\modules\user\forms;

use app\modules\core\components\FormModel;
use app\modules\core\interfaces\SaveModelInterface;
use app\modules\user\helpers\ModuleTrait;
use app\modules\user\models\ManagerUser;
use yii\base\Model;

/**
 * Class PasswordForm
 * @package app\modules\user\forms
 */
class PasswordForm extends FormModel implements SaveModelInterface
{
    use ModuleTrait;

    public $password;

    public $r_password;

    public $email;


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
            [['email'], 'safe'],
        ];
    }


    /**
     * @param string $attribute
     */
    public function emptyOnInvalid($attribute)
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
        return 'password-form';
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
     * @param Model|ManagerUser $model
     * @return boolean
     * @throws \yii\base\Exception
     */
    public function processingData(Model $model)
    {
        return app()->userManager->changePasswordProfile($this->password, !$model->isNewRecord ? $model : app()->user->info);
    }
}