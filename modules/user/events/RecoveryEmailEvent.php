<?php
namespace app\modules\user\events;

use app\modules\user\models\User;
use app\modules\user\models\UserToken;
use yii\base\Event;

class RecoveryEmailEvent extends Event {

    /**
     * @var User
     */
    private $_user;

    /**
     * @var UserToken
     */
    private $_token;


    /**
     * @return User
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->_user = $user;
    }

    /**
     * @return UserToken
     */
    public function getToken()
    {
        return $this->_token;
    }

    /**
     * @param UserToken $token
     */
    public function setToken(UserToken $token)
    {
        $this->_token = $token;
    }
}