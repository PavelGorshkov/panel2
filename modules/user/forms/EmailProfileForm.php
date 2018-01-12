<?php
namespace app\modules\user\forms;

use app\modules\core\components\FormModel;
use app\modules\user\models\User;

/**
 * Class EmailProfileForm
 * @package app\modules\user\forms
 */
class EmailProfileForm extends FormModel {

    public $email;

    /**
     * @return array
     */
    public function rules() {

        return [
            ['email', 'required'],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => 'Email уже занят'],
            ['email', 'email'],
        ];
    }

    /**
     * @return string
     */
    public function formName() {

        return 'email-profile-form';
    }
}