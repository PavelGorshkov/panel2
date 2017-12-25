<?php
namespace app\modules\user\events;

use app\modules\user\models\UserToken;

class UserTokenEvent extends UserEvent {

    /**
     * @var UserToken
     */
    protected $_token;


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