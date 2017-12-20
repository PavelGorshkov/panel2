<?php
namespace app\modules\user\components;

use app\modules\user\models\User;
use app\modules\user\models\UserProfile;

/**
 * Class WebUser
 * @package app\modules\user\components
 *
 * @property User $identity
 * @property UserProfile $profile
 * @property User $info
 */
class WebUser extends \yii\web\User
{
    const SUCCESS_MESSAGE = 'success';

    const INFO_MESSAGE = 'info';

    const WARNING_MESSAGE = 'warning';

    const ERROR_MESSAGE = 'error';

    /**
     * @param string $permissionName
     * @param array $params
     * @param bool $allowCaching
     * @return bool
     */
    public function can($permissionName, $params = [], $allowCaching = true)
    {

        if (is_array($permissionName)) {

            foreach ($permissionName as $p) {

                if (parent::can($p, $params, $allowCaching)) return true;
            }

            return false;
        } else {

            return parent::can($permissionName, $params, $allowCaching);
        }
    }


    /**
     * Установка FLASH сообщения
     *
     * @param string $key
     * @param string $value
     * @param string|null $defaultValue
     */
    public function setFlash($key,$value) {

        if ($this->isGuest) return;

        app()->session->set($key, $value);
    }


    /**
     * Проверка на наличие FLASH сообщения
     *
     * @param string $key
     * @return bool
     */
    public function hasFlash($key) {

        if ($this->isGuest) return false;

        if (app()->request->isAjax) return false;

        return app()->session->has($key->id);
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
    public function getFlash($key, $defaultValue=null, $delete=true) {

        if ($this->isGuest) return null;

        if (app()->request->isAjax) return null;

        $message = app()->session->get($key, $defaultValue);

        if ($delete) app()->session->remove($key);

        return $message;
    }


    /**
     * Установка сообщения предупреждения
     * @param string $message
     */
    public function setWarningFlash($message) {

        $this->setFlash(self::WARNING_MESSAGE, $message);
    }


    /**
     * Установка успешного сообщения
     * @param $message
     */
    public function setSuccessFlash($message) {

        $this->setFlash(self::SUCCESS_MESSAGE, $message);
    }


    /**
     * Установка сообщения об ошибке
     * @param $message
     */
    public function setErrorFlash($message) {

        $this->setFlash(self::ERROR_MESSAGE, $message);
    }


    /**
     * Установка успешного сообщения
     * @param $message
     */
    public function setInfoFlash($message) {

        $this->setFlash(self::INFO_MESSAGE, $message);
    }


    /**
     * Получение профиля авторизованного пользователя
     *
     * @return \app\modules\user\models\UserProfile
     */
    public function getProfile() {

        return $this->identity->userProfile;
    }


    /**
     * Получение информации об авторизованном пользователе
     * @return User
     */
    public function getInfo() {

        return $this->identity;
    }


    /**
     * Получение роли авторизованного пользователя
     *
     * @return null|string
     */
    public function getRole() {

        switch ($this->identity->access_level) {

            case User::ACCESS_LEVEL_ADMIN: return Roles::ADMIN;
            case User::ACCESS_LEVEL_REDACTOR: return Roles::REDACTOR;
            case User::ACCESS_LEVEL_OBSERVER: return Roles::OBSERVER;

            default: return null;
        };
    }
}
