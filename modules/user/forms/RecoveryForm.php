<?php
namespace app\modules\user\forms;

use app\modules\user\models\User;
use yii\base\Model;

/**
 * Class RecoveryForm
 * @package app\modules\user\forms
 */
class RecoveryForm extends Model
{
    public $email;

    /**
     * @var User
     */
    protected $_user;

    /**
     * @return string
     */
    public function formName()
    {
        return 'recovery-email-form';
    }

    /**
     * @return array
     */
    public function rules()
    {

        return [
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'checkEmail'],
        ];
    }


    /**
     * @param string $attribute
     * @param null $params
     */
    public function checkEmail($attribute, /** @noinspection PhpUnusedParameterInspection */ $params = null)
    {
        if ($this->hasErrors() === false) {

            $this->_user = User::find()->email($this->email);

            if ($this->_user === null) {

                $this->addError($attribute, 'Email "' . $this->email . '" не найден или пользователь заблокирован!');
            }
        }
    }


    /**
     * @return User
     */
    public function getUser() {

        return $this->_user;
    }
}