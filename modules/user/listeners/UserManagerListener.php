<?php

namespace app\modules\user\listeners;

use app\modules\user\events\PasswordEvent;
use app\modules\user\events\TokenEvent;
use app\modules\user\events\UserEvent;
use app\modules\user\Module;
use DateTime;

/**
 * Class UserManagerListener
 * @package app\modules\user\listeners
 */
class UserManagerListener
{
    /**
     * @return Module
     */
    public static function module() {

        return app()->getModule('user');
    }

    /**
     * @param UserEvent $event
     */
    public static function onUserRegistration(UserEvent $event)
    {
        $user = $event->getUser();

        self::sendMessage(
            $user->email,
            'Регистрация на сайте "' . app()->name . '"',
            'welcome',
            [
                'email' => $user->email,
                'fullName' => $user->profile->full_name,
                'login' => $user->username
            ]
        );
    }


    /**
     * @param TokenEvent $event
     */
    public static function onUserRegistrationNeedActivation(TokenEvent $event)
    {
        $user = $event->getUser();

        self::sendMessage(
            $user->email,
            'Регистрация на сайте "' . app()->name . '"',
            'activation',
            [
                'email' => $user->email,
                'fullName' => $user->profile->full_name,
                'login' => $user->username,
                'token' => $event->getToken(),
                'expire' => self::setExpireDateTime(self::module()->expireTokenActivationLifeHours * 3600),
            ]
        );
    }


    /**
     * @param TokenEvent $event
     */
    public static function onUserRecoveryPassword(TokenEvent $event)
    {
        $user = $event->getUser();

        self::sendMessage(
            $user->email,
            'Восстановление пароля на сайте "' . app()->name . '"',
            'recovery',
            [
                'email' => $user->email,
                'fullName' => $user->profile->full_name,
                'token' => $event->getToken(),
                'expire' => self::setExpireDateTime((self::module()->expireTokenPasswordLifeHours * 3600)),
            ]
        );
    }


    /**
     * @param PasswordEvent $event
     */
    public static function onUserGeneratePassword(PasswordEvent $event)
    {
        $user = $event->getUser();

        self::sendMessage(
            $user->email,
            'Восстановление пароля на сайте "' . app()->name . '"',
            'generatePassword',
            [
                'email' => $user->email,
                'fullName' => $user->profile->full_name,
                'password' => $event->getPassword(),
            ]
        );
    }


    /**
     * @param UserEvent $event
     */
    public static function onUserChangePassword(UserEvent $event)
    {
        $user = $event->getUser();

        self::sendMessage(
            $user->email,
            'Восстановление пароля на сайте "' . app()->name . '"',
            'changePassword',
            [
                'email' => $user->email,
                'fullName' => $user->profile->full_name,
            ]
        );
    }


    /**
     * @param TokenEvent $event
     */
    public static function onUserChangeEmail(TokenEvent $event)
    {
        $user = $event->getUser();

        self::sendMessage(
            $user->email,
            'Активация электронной почты',
            'changeEmail',
            [
                'fullName' => $user->profile->full_name,
                'token' => $event->getToken(),
                'expire' => self::setExpireDateTime((self::module()->expireTokenActivationLifeHours * 3600)),
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

        return $mailer->compose(['html' => $view, 'text' => 'text/' . $view], $params)
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
    protected static function setExpireDateTime($expire)
    {
        $datetime = new DateTime();
        $interval = new \DateInterval('PT' . $expire . 'S');

        return $datetime->add($interval)->format('d.m.Y H:i:s');
    }
}