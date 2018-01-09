<?php
namespace app\modules\user\forms;

use app\modules\user\models\User;
use yii\base\Model;

class RecoveryForm extends Model
{
    public $email;

    protected $_user;

    public function formName()
    {
        return 'recovery-email-form';
    }

    public function rules()
    {

        return [
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'checkEmail'],
        ];
    }


    public function checkEmail($attribute, /** @noinspection PhpUnusedParameterInspection */ $params = null)
    {
        if ($this->hasErrors() === false) {

            $this->_user = User::find()->email($this->email);

            if ($this->_user === null) {

                $this->addError($attribute, 'Email "' . $this->email . '" не найден или пользователь заблокирован!');
            }
        }
    }

    public function getUser() {

        return $this->_user;
    }
}