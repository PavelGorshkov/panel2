<?php
namespace app\modules\user\events;

/**
 * Class PasswordEvent
 * @package app\modules\user\events
 */
class PasswordEvent extends UserEvent {

    /**
     * @var string
     */
    protected $_password;


    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }


    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
    }
}