<?php

namespace app\modules\user\components;

use app\modules\user\helpers\UserAccessLevelHelper;
use app\modules\user\models\IdentityUser;
use app\modules\user\models\User;
use yii\web\User as ParentWebUser;

/**
 * Class WebUser
 * @package app\modules\user\components
 *
 * @property IdentityUser $identity
 * @property-read IdentityUser $info
 */
class WebUser extends ParentWebUser
{
    const SUCCESS_MESSAGE = 'success';

    const INFO_MESSAGE = 'info';

    const WARNING_MESSAGE = 'warning';

    const ERROR_MESSAGE = 'error';

    protected $_access = null;

    /**
     * @var User
     */
    protected $_info = null;


    /**
     * @param array|string $permissionName
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

        $this->setFlash(self::WARNING_MESSAGE, $message);
    }


    /**
     * Установка успешного сообщения
     *
     * @param $message
     */
    public function setSuccessFlash($message)
    {

        $this->setFlash(self::SUCCESS_MESSAGE, $message);
    }


    /**
     * Установка сообщения об ошибке
     * @param $message
     */
    public function setErrorFlash($message)
    {

        $this->setFlash(self::ERROR_MESSAGE, $message);
    }


    /**
     * Установка успешного сообщения
     * @param $message
     */
    public function setInfoFlash($message)
    {

        $this->setFlash(self::INFO_MESSAGE, $message);
    }


    /**
     * Получение информации об авторизованном пользователе
     * @return User
     */
    public function getInfo()
    {

        if ($this->isGuest) return null;

        if ($this->_info === null) $this->_info = $this->identity;

        return $this->_info;
    }

    /**
     * Получение роли авторизованного пользователя
     *
     * @return null|string
     */
    public function getRole()
    {

        if ($this->isGuest) return Roles::GUEST;

        $roles = UserAccessLevelHelper::listRoles();

        if (isset($roles[$this->identity->access_level])) {

            return $roles[$this->identity->access_level];
        }

        return null;
    }


    /**
     * @return array|null
     */
    public function getAccessData()
    {

        if ($this->isGuest) return [];

        if ($this->_access === null) {

            $this->_access = app()->userManager->getAccessForUser($this->identity);
        }

        return $this->_access;
    }
}
