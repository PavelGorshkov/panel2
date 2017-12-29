<?php
namespace app\modules\user\listeners;

use app\modules\user\events\PasswordEvent;
use app\modules\user\events\TokenEvent;
use app\modules\user\events\RegistrationEvent;
use app\modules\user\events\UserEvent;
use DateTime;

class UserManagerListener {


    /**
     * @param RegistrationEvent $event
     */
    public static function onUserRegistration(RegistrationEvent $event) {

        $registration = $event->getRegistrationForm();

        self::sendMessage(
            $registration->email,
            'Регистрация на сайте "'.app()->name.'"',
            'welcome',
            [
                'email'=>$registration->email,
                'fullName'=>$registration->full_name,
                'login'=>$registration->username
            ]
        );

    }


    /**
     * @param RegistrationEvent $event
     */
    public static function onUserRegistrationNeedActivation(RegistrationEvent $event) {

        $registration = $event->getRegistrationForm();

        self::sendMessage(
            $registration->email,
            'Регистрация на сайте "'.app()->name.'"',
            'activation',
            [
                'email'=>$registration->email,
                'fullName'=>$registration->full_name,
                'login'=>$registration->username,
                'token'=>$event->getToken(),
                'expire'=>self::setExpireDateTime(app()->getModule('user')->expireTokenActivationLifeHours*3600),
            ]
        );

    }


    /**
     * @param TokenEvent $event
     */
    public static function onUserRecoveryPassword(TokenEvent $event) {

        $user = $event->getUser();

        self::sendMessage(
            $user->email,
            'Восстановление пароля на сайте "'.app()->name.'"',
            'recovery',
            [
                'email'=>$user->email,
                'fullName'=>$user->userProfile->full_name,
                'token'=>$event->getToken(),
                'expire'=>self::setExpireDateTime((app()->getModule('user')->expireTokenPasswordLifeHours*3600)),
            ]
        );
    }


    /**
     * @param PasswordEvent $event
     */
    public static function onUserGeneratePassword(PasswordEvent $event) {

        $user = $event->getUser();

        self::sendMessage(
            $user->email,
            'Восстановление пароля на сайте "'.app()->name.'"',
            'generatePassword',
            [
                'email'=>$user->email,
                'fullName'=>$user->userProfile->full_name,
                'password'=>$event->getPassword(),
            ]
        );
    }


    /**
     * @param UserEvent $event
     */
    public static function onUserChangePassword(UserEvent $event) {

        $user = $event->getUser();

        self::sendMessage(
            $user->email,
            'Восстановление пароля на сайте "'.app()->name.'"',
            'changePassword',
            [
                'email'=>$user->email,
                'fullName'=>$user->userProfile->full_name,
            ]
        );
    }


    /**
     * @param TokenEvent $event
     */
    public static function onUserChangeEmail(TokenEvent $event) {

        $user = $event->getUser();

        self::sendMessage(
            $user->email,
            'Активация электронной почты',
            'changeEmail',
            [
                'fullName'=>$user->userProfile->full_name,
                'token'=>$event->getToken(),
                'expire'=>self::setExpireDateTime((app()->getModule('user')->expireTokenActivationLifeHours*3600)),
            ]
        );
    }


    /**
     * @param string $to
     * @param string $subject
     * @param string $view
     * @param array $params
     *
     * @return bool
     */
    protected static function sendMessage($to, $subject, $view = null, $params = [])
    {
        /** @var \yii\mail\BaseMailer $mailer */
        $mailer = app()->mailer;
        $mailer->viewPath = '@app/modules/user/views/mail';

        $mailer->getView()->theme = app()->view->theme;
        $mailer->getView()->title = $subject;

        return $mailer->compose(['html'=>$view,'text'=>'text/'.$view], $params)
            ->setTo($to)
            ->setFrom(app()->params['email'])
            ->setSubject($mailer->getView()->title)
            ->send();
    }


    /**
     * @param integer $expire
     *
     * @return string
     */
    protected static function setExpireDateTime($expire) {

        $datetime = new DateTime();
        $interval = new \DateInterval('PT'.$expire.'S');

        return $datetime->add($interval)->format('d.m.Y H:i:s');
    }
}