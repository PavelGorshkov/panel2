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

                if (parent::can($permissionName, $params, $allowCaching)) return true;
            }

            return false;
        } else {

            return parent::can($permissionName, $params, $allowCaching);
        }
    }

    public function setFlash($key,$value,$defaultValue=null) {

        if ($this->isGuest) return;

        app()->session->set($key.user()->id, $value);
    }


    public function hasFlash($key) {

        if ($this->isGuest) return;

        return app()->session->has($key.user()->id);
    }


    public function getFlash($key, $defaultValue=null, $delete=true) {

        if ($this->isGuest) return;

        if (app()->request->isAjax) return;

        $message = app()->session->get($key.user()->id, $defaultValue);

        if ($delete) app()->session->remove($key.user()->id);

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
