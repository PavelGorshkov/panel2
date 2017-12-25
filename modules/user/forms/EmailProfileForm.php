<?php
namespace app\modules\user\forms;

use app\modules\core\components\FormModel;
use app\modules\user\models\User;

class EmailProfileForm extends FormModel {

    public $email;

    public function rules() {

        return [
            ['email', 'required'],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => 'Email уже занят'],
            ['email', 'email'],
        ];
    }

    public function formName() {

        return 'email-profile-form';
    }
}