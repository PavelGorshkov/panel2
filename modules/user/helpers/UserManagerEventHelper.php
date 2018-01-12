<?php
namespace app\modules\user\helpers;

use app\modules\user\components\UserManager;
use app\modules\user\events\PasswordEvent;
use app\modules\user\events\TokenEvent;
use app\modules\user\events\UserEvent;
use app\modules\user\models\User;
use app\modules\user\models\Token;
use Yii;

/**
 * Class UserManagerEventHelper
 * @package app\modules\user\helpers
 *
 * @method on(string $name, callable $handler, mixed $data = null, bool $append = true)
 */
trait UserManagerEventHelper {

    protected function setListener() {

        $this->on(
            UserManager::EVENT_SUCCESS_REGISTRATION,
            ['app\modules\user\listeners\UserManagerListener', 'onUserRegistration']
        );

        $this->on(
            UserManager::EVENT_SUCCESS_REGISTRATION_NEED_ACTIVATION,
            ['app\modules\user\listeners\UserManagerListener', 'onUserRegistrationNeedActivation']
        );

        $this->on(
            UserManager::EVENT_RECOVERY_PASSWORD,
            ['app\modules\user\listeners\UserManagerListener', 'onUserRecoveryPassword']
        );

        $this->on(
            UserManager::EVENT_GENERATE_PASSWORD,
            ['app\modules\user\listeners\UserManagerListener', 'onUserGeneratePassword']
        );

        $this->on(
            UserManager::EVENT_CHANGE_PASSWORD,
            ['app\modules\user\listeners\UserManagerListener', 'onUserChangePassword']
        );

        $this->on(
            UserManager::EVENT_CHANGE_EMAIL,
            ['app\modules\user\listeners\UserManagerListener', 'onUserChangeEmail']
        );
    }


    /**
     * @param User $user
     * @param Token $token
     *
     * @return TokenEvent
     *
     * @throws \yii\base\InvalidConfigException
     */
    protected function getUserTokenEvent(User $user, Token $token) {

        /* @var $event TokenEvent */
        $event =  Yii::createObject([
            'class' => TokenEvent::className(),
            'user' => $user,
            'token' => $token,
        ]);

        return $event;
    }


    /**
     * @param User $user
     * @param string $password
     *
     * @return PasswordEvent
     * @throws \yii\base\InvalidConfigException
     */
    protected function getUserPasswordEvent(User $user, $password) {

        /* @var $event PasswordEvent */
        $event =  Yii::createObject([
            'class' => PasswordEvent::className(),
            'user' => $user,
            'password' => $password,
        ]);

        return $event;
    }


    /**
     * @param User $user
     *
     * @return UserEvent|object
     * @throws \yii\base\InvalidConfigException
     */
    protected function getUserEvent(User $user) {

        return Yii::createObject([
            'class' => UserEvent::className(),
            'user' => $user,
        ]);
    }
}