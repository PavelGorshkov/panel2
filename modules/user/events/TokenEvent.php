<?php

namespace app\modules\user\events;

use app\modules\user\models\Token;

/**
 * Class TokenEvent
 * @package app\modules\user\events
 */
class TokenEvent extends UserEvent
{

    /**
     * @var Token
     */
    protected $_token;


    /**
     * @return Token
     */
    public function getToken()
    {
        return $this->_token;
    }

    /**
     * @param Token $token
     */
    public function setToken(Token $token)
    {
        $this->_token = $token;
    }
}