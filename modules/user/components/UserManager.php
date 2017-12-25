<?php
namespace app\modules\user\components;

use app\modules\user\forms\ProfileForm;
use app\modules\user\forms\ProfileRegistrationForm;
use app\modules\user\forms\RegistrationForm;
use app\modules\user\helpers\EmailConfirmStatusHelper;
use app\modules\user\helpers\ModuleTrait;
use app\modules\user\helpers\Password;
use app\modules\user\helpers\RegisterFromHelper;
use app\modules\user\helpers\UserManagerEventHelper;
use app\modules\user\helpers\UserStatusHelper;
use app\modules\user\models\query\UserAccessQuery;
use app\modules\user\models\query\UserQuery;
use app\modules\user\models\User;
use app\modules\user\models\UserAccess;
use app\modules\user\models\UserProfile;
use app\modules\user\models\UserToken;
use Yii;
use yii\base\Component;
use yii\db\Expression;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;
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

    const EVENT_RECOVERY_PASSWORD = 'user.recovery.password';

    const EVENT_GENERATE_PASSWORD = 'user.generate.password';

    const EVENT_CHANGE_PASSWORD = 'user.change.password';

    const EVENT_CHANGE_EMAIL = 'user.change.email';

    use ModuleTrait;
    use UserManagerEventHelper;

    /**
     * @var UserQuery
     */
    protected $userQuery;

    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @var UserAccessQuery
     */
    protected $userAccessQuery;


    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init() {

        parent::init();

        $this->userQuery = User::find();

        $this->userAccessQuery = UserAccess::find();

        $this->setTokenStorage(Yii::createObject(['class' => TokenStorage::className()]));

        $this->setListener();
    }

    /**
     * @param TokenStorage $tokenStorage
     */
    public function setTokenStorage(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }


    /**
     * @param $usernameOrEmail
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        return $this->userQuery
            ->findUser('username = :user OR email = :user', [':user' => $usernameOrEmail])
            ->one();
    }


    public function findUserById($id) {

        return $this->userQuery
            ->findUser('id = :id', [':id' => (int) $id])
            ->one();
    }


    /**
     * Регистрация нового пользователя через Форму
     *
     * @param RegistrationForm $model
     * @param ProfileRegistrationForm $profile
     * @return bool
     * @throws ServerErrorHttpException
     * @throws \yii\db\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function registerForm(RegistrationForm $model, ProfileRegistrationForm $profile) {

        $event = $this->getRegistrationEvent($model, $profile);

        /* заполнение пользователя */
        $user = $this->createUserForRegistration($model);

        $transaction  = app()->db->beginTransaction();

        if (!$user->save()) $this->failureTransaction($transaction);

        $event->setUser($user);

        $userProfile = $this->createUserProfileForRegistration($model, $profile, $user);

        if (!$userProfile->save()) $this->failureTransaction($transaction);

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
     * @param User $user
     *
     * @return User
     */
    protected function setUserStatusTypeAndEmailConfirm(User $user) {

        if (!$this->module->emailAccountVerification) {

            $user->status = UserStatusHelper::STATUS_ACTIVE;
            $user->email_confirm = EmailConfirmStatusHelper::EMAIL_CONFIRM_YES;
        } else {

            $user->status = UserStatusHelper::STATUS_NOT_ACTIVE;
            $user->email_confirm = EmailConfirmStatusHelper::EMAIL_CONFIRM_NO;
        }

        return $user;
    }


    /**
     * @param Transaction $transaction
     * @return bool
     *
     * @throws \yii\db\Exception
     */
    protected function failureTransaction(Transaction $transaction) {

        $transaction->rollBack();

        return false;
    }


    /**
     * @param RegistrationForm $model
     *
     * @return User
     */
    protected function createUserForRegistration(RegistrationForm $model) {

        $user = new User();
        $user->setScenario(User::SCENARIO_REGISTER);

        $user->setAttributes($model->getAttributes());

        $user = $this->setUserStatusTypeAndEmailConfirm($user);

        $user->hash = Password::hash($model->password);

        $user->registered_from = RegisterFromHelper::FORM;

        return $user;
    }


    /**
     * @param RegistrationForm $model
     * @param ProfileRegistrationForm $modelProfile
     * @param User $user
     * 
     *
     * @return UserProfile
     */
    protected function createUserProfileForRegistration(RegistrationForm $model, ProfileRegistrationForm $modelProfile, User $user) {

        $profile = new UserProfile();

        $profile->setAttributes($model->getAttributes());
        $profile->setAttributes($modelProfile->getAttributes());
        $profile->user_id = $user->id;

        return $profile;
    }


    /**
     * Проверка Email
     *
     * @param string $token
     * @param integer $tokenType
     * @return bool
     * @throws \yii\db\Exception
     */
    public function verifyEmail($token, $tokenType) {

        /* @var User $user */
        /* @var UserToken $tokenModel */
        list($tokenModel, $user) = $this->getTokenUserList($token, $tokenType);

        if ($user === null || $tokenModel === null)  return false;

        $transaction = app()->db->beginTransaction();

        if (!$user->updateAttributes(
            [
                'status'=>UserStatusHelper::STATUS_ACTIVE,
                'status_change_at'=>new Expression('NOW()'),
                'email_confirm'=>EmailConfirmStatusHelper::EMAIL_CONFIRM_YES,
            ]
        )) return $this->failureTransaction($transaction);

        if (!$this->tokenStorage->delete($tokenModel)) return $this->failureTransaction($transaction);

        $transaction->commit();
        return true;
    }


    /**
     * @param $token
     * @param $tokenType
     * @return array list(UserToken, User)
     */
    public function getTokenUserList($token, $tokenType) {

        $tokenModel =  $this->tokenStorage->getToken($token, $tokenType);

        if ($tokenModel === null) return [null, null];

        return [$tokenModel, $this->findUserById($tokenModel->user_id)];
    }



    /**
     * @param User $user
     *
     * @return array
     */
    public function getAccessForUser(User $user) {

        if ($user === null) return [];

        $data = $this->userAccessQuery->getDataForUser($user->id);

        if ($user->isUFAccessLevel()) {

            $data = ArrayHelper::merge($data, $this->userAccessQuery->getDataForRole($user->access_level));
        }

        return $data;
    }


    /**
     * @param User $user
     * @param UserToken $token
     * @return bool
     * @throws \yii\db\Exception
     */
    public function generatePassword(User $user, UserToken $token) {

        $password = Password::generate(8);

        $transaction = app()->db->beginTransaction();

        if (!$user->updateAttributes(['hash'=>Password::hash($password)])) return $this->failureTransaction($transaction);

        $this->trigger(self::EVENT_GENERATE_PASSWORD, $this->getUserPasswordEvent($user, $password));

        if (!$this->tokenStorage->delete($token)) return $this->failureTransaction($transaction);

        $transaction->commit();

        return true;
    }


    /**
     * Восстановление пароля, отправка токена смены пароля
     *
     * @param User $user
     *
     * @return bool
     * @throws \yii\db\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function recoverySendMail(User $user) {

        if (null === $user) return false;

        $transaction = app()->getDb()->beginTransaction();

        $token = $this->tokenStorage->createPasswordToken($user);

        if ($token === null) return $this->failureTransaction($transaction);

        $this->trigger(self::EVENT_RECOVERY_PASSWORD, $this->getUserTokenEvent($user, $token));

        $transaction->commit();

        return true;
    }


    /**
     * @param User $user
     * @param UserToken $token
     * @param string $password
     *
     * @return bool
     *
     * @throws \yii\db\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function changePassword(User $user, UserToken $token, $password) {

        $transaction = app()->db->beginTransaction();

        if (!$user->updateAttributes(['hash'=>Password::hash($password)])) return $this->failureTransaction($transaction);

        if (!$this->tokenStorage->delete($token)) return $this->failureTransaction($transaction);

        $this->trigger(self::EVENT_CHANGE_PASSWORD, $this->getUserEvent($user));

        $transaction->commit();

        return true;
    }


    /**
     * @param User $user
     * @param string $email
     *
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function changeEmail(User $user, $email) {

        if ($email==$user->email && $user->isConfirmedEmail()) return true;

        $transaction = app()->db->beginTransaction();

        $user = $this->setUserStatusTypeAndEmailConfirm($user);
        $user->email = $email;

        if (!$user->save()) return $this->failureTransaction($transaction);

        $token = $this->tokenStorage->createEmailActivationToken($user);

        if ($token === false) {

            $transaction->rollBack();

            return false;
        }

        $this->trigger(self::EVENT_CHANGE_EMAIL, $this->getUserTokenEvent($user, $token));

        $transaction->commit();

        return true;
    }


    /**
     * @param ProfileForm $model
     *
     * @return bool
     */
    public function saveProfile(ProfileForm $model) {

        $data = $model->getAttributes();
        $data['avatar'] = $data['avatar_file'];
        unset($data['avatar_file']);
        unset($data['email']);

        return user()->identity->userProfile->updateAttributes($data);
    }
}