<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 14.12.2017
 * Time: 15:30
 */

namespace app\modules\user\events;

use app\modules\user\forms\ProfileRegistrationForm;
use app\modules\user\forms\RegistrationForm;
use app\modules\user\models\UserToken;


class RegistrationEvent extends UserEvent {

    /**
     * @var RegistrationForm
     */
    private $_registration_form;

    /**
     * @var ProfileRegistrationForm
     */
    private $_profile_registration_form;

    /**
     * @var UserToken
     */
    private $_token;


    /**
     * @return RegistrationForm
     */
    public function getRegistrationForm()
    {
        return $this->_registration_form;
    }

    /**
     * @param RegistrationForm $form
     */
    public function setRegistrationForm(RegistrationForm $form)
    {
        $this->_registration_form = $form;
    }


    /**
     * @param ProfileRegistrationForm $form
     */
    public function setProfileRegistrationForm(ProfileRegistrationForm $form)
    {
        $this->_profile_registration_form = $form;
    }


    /**
     * @return ProfileRegistrationForm
     */
    public function getProfileRegistrationForm()
    {
        return $this->_profile_registration_form;
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