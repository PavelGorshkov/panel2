<?php
namespace app\modules\user\listeners;

use app\modules\user\events\UserPasswordEvent;
use app\modules\user\events\UserTokenEvent;
use app\modules\user\events\RegistrationEvent;
use app\modules\user\events\UserEvent;
use app\modules\user\Module;
use DateTime;

class UserManagerListener {

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

    public static function onUserRegistrationNeedActivation(RegistrationEvent $event) {

        $registration = $event->getRegistrationForm();
        $token = $event->getToken();

        /* @var Module $module */
        $module = app()->getModule('user');


        $expire = new DateTime();
        $interval = new \DateInterval('PT'.($module->expireTokenActivationLifeHours*3600).'S');
        $date = $expire->add($interval)->format('d.m.Y H:i:s');

        self::sendMessage(
            $registration->email,
            'Регистрация на сайте "'.app()->name.'"',
            'activation',
            [
                'email'=>$registration->email,
                'fullName'=>$registration->full_name,
                'login'=>$registration->username,
                'token'=>$token,
                'expire'=>$date,
            ]
        );

    }

    public static function onUserFailureRegistration(RegistrationEvent $event) {}


    public static function onUserRecoveryPassword(UserTokenEvent $event) {

        $user = $event->getUser();
        $token = $event->getToken();

        $expire = new DateTime();
        $interval = new \DateInterval('PT'.(app()->getModule('user')->expireTokenPasswordLifeHours*3600).'S');
        $date = $expire->add($interval)->format('d.m.Y H:i:s');

        self::sendMessage(
            $user->email,
            'Восстановление пароля на сайте "'.app()->name.'"',
            'recovery',
            [
                'email'=>$user->email,
                'fullName'=>$user->userProfile->full_name,
                'token'=>$token,
                'expire'=>$date,
            ]
        );
    }


    public static function onUserGeneratePassword(UserPasswordEvent $event) {

        $user = $event->getUser();

        $password = $event->getPassword();

        self::sendMessage(
            $user->email,
            'Восстановление пароля на сайте "'.app()->name.'"',
            'generatePassword',
            [
                'email'=>$user->email,
                'fullName'=>$user->userProfile->full_name,
                'password'=>$password,
            ]
        );
    }


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


    public static function ononUserChangeEmail(UserTokenEvent $event) {

        $user = $event->getUser();
        $token = $event->getToken();
    }


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


}