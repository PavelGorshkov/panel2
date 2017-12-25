<?php
namespace app\modules\user\events;


class UserPasswordEvent extends UserEvent {

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