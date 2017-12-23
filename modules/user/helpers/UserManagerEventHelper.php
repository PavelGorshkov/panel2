<?php
namespace app\modules\user\helpers;


use app\modules\user\components\UserManager;
use app\modules\user\events\GeneratePasswordEvent;
use app\modules\user\events\RecoveryEmailEvent;
use app\modules\user\events\RegistrationEvent;
use app\modules\user\events\UserEvent;
use app\modules\user\forms\ProfileRegistrationForm;
use app\modules\user\forms\RegistrationForm;
use app\modules\user\models\User;
use app\modules\user\models\UserToken;
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
            UserManager::EVENT_FAILURE_REGISTRATION,
            ['app\modules\user\listeners\UserManagerListener', 'onUserFailureRegistration']
        );

        $this->on(
            UserManager::EVENT_RECOVERY_EMAIL,
            ['app\modules\user\listeners\UserManagerListener', 'onUserRecoveryEmail']
        );

        $this->on(
            UserManager::EVENT_GENERATE_PASSWORD,
            ['app\modules\user\listeners\UserManagerListener', 'onUserGeneratePassword']
        );

        $this->on(
            UserManager::EVENT_CHANGE_PASSWORD,
            ['app\modules\user\listeners\UserManagerListener', 'onUserChangePassword']
        );
    }

    /**
     * @param RegistrationForm $registrationForm
     * @param ProfileRegistrationForm $profileRegistrationForm
     * @return RegistrationEvent
     * @throws \yii\base\InvalidConfigException
     */
    protected function getRegistrationFormEvent(RegistrationForm $registrationForm, ProfileRegistrationForm $profileRegistrationForm) {

        return Yii::createObject([
            'class' => RegistrationEvent::className(),
            'registrationForm' => $registrationForm,
            'profileRegistrationForm' => $profileRegistrationForm,
        ]);
    }


    /**
     * @param User $user
     * @param UserToken $token
     *
     * @return RecoveryEmailEvent
     *
     * @throws \yii\base\InvalidConfigException
     */
    protected function getRecoveryEmailEvent(User $user, UserToken $token) {

        return Yii::createObject([
            'class' => RecoveryEmailEvent::className(),
            'user' => $user,
            'token' => $token,
        ]);
    }


    /**
     * @param User $user
     * @param string $password
     *
     * @return GeneratePasswordEvent
     * @throws \yii\base\InvalidConfigException
     */
    protected function getGeneratePasswordEvent(User $user, $password) {

        return Yii::createObject([
            'class' => GeneratePasswordEvent::className(),
            'user' => $user,
            'password' => $password,
        ]);
    }


    /**
     * @param User $user
     * @param string $password
     *
     * @return GeneratePasswordEvent
     * @throws \yii\base\InvalidConfigException
     */
    protected function getChangePasswordEvent(User $user) {

        return Yii::createObject([
            'class' => UserEvent::className(),
            'user' => $user,
        ]);
    }

}