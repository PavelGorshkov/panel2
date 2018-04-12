<?php

namespace app\modules\user\helpers;

use app\modules\user\components\WebUser;

/**
 * Trait UserFlashTrait
 * @package app\modules\user\helpers
 */
trait UserFlashTrait
{
    /**
     * Установка FLASH сообщения
     *
     * @param string $key
     * @param string $value
     */
    public function setFlash($key, $value)
    {
        app()->session->set($key, $value);
    }


    /**
     * Проверка на наличие FLASH сообщения
     *
     * @param string $key
     * @return bool
     */
    public function hasFlash($key)
    {
        if (app()->request->isAjax) return false;

        return app()->session->has($key);
    }


    /**
     * Получение FLASH сообщения
     *
     * @param string $key
     * @param string $defaultValue
     * @param bool $delete
     *
     * @return string|null
     */
    public function getFlash($key, $defaultValue = null, $delete = true)
    {
        if (app()->request->isAjax) return null;

        $message = app()->session->get($key, $defaultValue);

        if ($delete) app()->session->remove($key);

        return $message;
    }


    /**
     * Установка сообщения предупреждения
     *
     * @param string $message
     */
    public function setWarningFlash($message)
    {
        $this->setFlash(WebUser::WARNING_MESSAGE, $message);
    }


    /**
     * Установка успешного сообщения
     *
     * @param $message
     */
    public function setSuccessFlash($message)
    {
        $this->setFlash(WebUser::SUCCESS_MESSAGE, $message);
    }


    /**
     * Установка сообщения об ошибке
     * @param $message
     */
    public function setErrorFlash($message)
    {
        $this->setFlash(WebUser::ERROR_MESSAGE, $message);
    }


    /**
     * Установка успешного сообщения
     * @param $message
     */
    public function setInfoFlash($message)
    {

        $this->setFlash(WebUser::INFO_MESSAGE, $message);
    }
}