<?php
namespace app\modules\user\components;


class WebUser extends \yii\web\User
{
    const SUCCESS_MESSAGE = 'success';

    const INFO_MESSAGE = 'info';

    const WARNING_MESSAGE = 'warning';

    const ERROR_MESSAGE = 'error';

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

    public function setFlash($key,$value,$defaultValue=null) {

        if ($this->isGuest) return false;

        app()->session->set($key, $value);
    }


    public function hasFlash($key) {

        if ($this->isGuest) return false;

        if (app()->request->isAjax) return false;

        return app()->session->has($key->id);
    }


    public function getFlash($key, $defaultValue=null, $delete=true) {

        if ($this->isGuest) return false;

        if (app()->request->isAjax) return false;

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
}
