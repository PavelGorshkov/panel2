<?php
namespace app\modules\user\events;


class GeneratePasswordEvent extends UserEvent {

    /**
     * @var string
     */
    private $_password;


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