<?php
namespace app\modules\user\listeners;

use app\modules\user\events\RegistrationEvent;
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

        $expire = new DateTime();
        $interval = new \DateInterval('PT'.(app()->getModule('user')->expireTokenActivationLifeHours*3600).'S');
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

    public static function onUserFailureRegistration(RegistrationEvent $event) {



    }


    protected static function sendMessage($to, $subject, $view = null, $params = [])
    {
        /** @var \yii\mail\BaseMailer $mailer */
        $mailer = app()->mailer;
        $mailer->viewPath = '@app/modules/user/views/mail';

        $mailer->getView()->theme = app()->view->theme;
        $mailer->getView()->title = $subject;

        return $mailer->compose(['html'=>$view,'text'=>'text/'.$view], $params)
            ->setTo($params['email'])
            ->setFrom(app()->params['email'])
            ->setSubject($mailer->getView()->title)
            ->send();
    }
}