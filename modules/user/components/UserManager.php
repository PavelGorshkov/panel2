<?php
namespace app\modules\user\components;

use app\modules\user\events\RegistrationEvent;
use app\modules\user\helpers\EmailConfirmStatusHelper;
use app\modules\user\helpers\ModuleTrait;
use app\modules\user\helpers\Password;
use app\modules\user\helpers\UserStatusHelper;
use app\modules\user\models\ProfileRegistrationForm;
use app\modules\user\models\query\UserQuery;
use app\modules\user\models\RegistrationForm;
use app\modules\user\models\User;
use app\modules\user\models\UserProfile;
use app\modules\user\models\UserToken;
use Yii;
use yii\base\Component;
use yii\web\ServerErrorHttpException;

/**
 * Компонент для управления пользовательскими данными
 *
 * Class UserManager
 * @package app\modules\user\components
 *
 *
 */
class UserManager extends Component {

    const EVENT_SUCCESS_REGISTRATION = 'user.success.registration';
    const EVENT_SUCCESS_REGISTRATION_NEED_ACTIVATION = 'user.success.registration.need.activation';
    const EVENT_FAILURE_REGISTRATION = 'user.failure.registration';

    use ModuleTrait;

    /**
     * @var UserQuery
     */
    protected $userQuery;

    /**
     * @var TokenStorage
     */
    protected $tokenStorage;


    public function init() {

        parent::init();

        $this->userQuery = User::find();

        $this->setTokenStorage(Yii::createObject([
            'class' => TokenStorage::className(),
        ]));

        $this->on(
            self::EVENT_SUCCESS_REGISTRATION,
            ['app\modules\user\listeners\UserManagerListener', 'onUserRegistration']
        );

        $this->on(
            self::EVENT_SUCCESS_REGISTRATION_NEED_ACTIVATION,
            ['app\modules\user\listeners\UserManagerListener', 'onUserRegistrationNeedActivation']
        );

        $this->on(
            self::EVENT_FAILURE_REGISTRATION,
            ['app\modules\user\listeners\UserManagerListener', 'onUserFailureRegistration']
        );
    }


    public function setTokenStorage(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }


    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        return $this->userQuery
            ->findUser('username = :user OR email = :user', [':user' => $usernameOrEmail])
            ->one();
    }


    /**
     * Регистрация пользователя
     *
     * @param RegistrationForm $model
     * @param ProfileRegistrationForm $profile
     * @return bool
     */
    public function register(RegistrationForm $model, ProfileRegistrationForm $profile) {

        $event = $this->getFormRegistrationEvent($model, $profile);

        /* заполнение пользователя */
        $user = new User();
        $user->setScenario(User::SCENARIO_REGISTER);

        $user->setAttributes($model->getAttributes());

        if (!$this->module->emailAccountVerification) {

            $user->status = UserStatusHelper::STATUS_ACTIVE;
            $user->email_confirm = EmailConfirmStatusHelper::EMAIL_CONFIRM_YES;
        } else {

            $user->status = UserStatusHelper::STATUS_NOT_ACTIVE;
            $user->email_confirm = EmailConfirmStatusHelper::EMAIL_CONFIRM_NO;
        }

        $user->hash = Password::hash($model->password);

        $transaction  = app()->db->beginTransaction();

        if (!$user->save()) {

            $transaction->rollBack();

            $this->trigger(self::EVENT_FAILURE_REGISTRATION, $event);

            throw new ServerErrorHttpException('Не удалось сохранить в БД данные пользователя');
        }

        $event->setUser($user);
        $userProfile = new UserProfile();

        $userProfile->setAttributes($model->getAttributes());
        $userProfile->setAttributes($profile->getAttributes());

        $userProfile->user_id = $user->id;

        if (!$userProfile->save()) {

            $transaction->rollBack();

            $this->trigger(self::EVENT_FAILURE_REGISTRATION, $event);

            throw new ServerErrorHttpException('Не удалось сохранить в БД данные профиля');
        }

        if (!$this->module->emailAccountVerification) {

            $this->trigger(self::EVENT_SUCCESS_REGISTRATION, $event);

        } else {

            $event->setToken($this->tokenStorage->createAccountActivationToken($user));

            $this->trigger(self::EVENT_SUCCESS_REGISTRATION_NEED_ACTIVATION, $event);
        }

        $transaction->commit();

        return true;
    }


    /**
     * @param RegistrationForm $registrationForm
     * @param ProfileRegistrationForm $profileRegistrationForm
     * @param User $user
     * @param UserToken $token
     * @return RegistrationEvent
     * @throws \yii\base\InvalidConfigException
     */
    protected function getFormRegistrationEvent(RegistrationForm $registrationForm, ProfileRegistrationForm $profileRegistrationForm) {

        return Yii::createObject([
            'class' => RegistrationEvent::className(),
            'registrationForm' => $registrationForm,
            'profileRegistrationForm' => $profileRegistrationForm,
        ]);
    }
}