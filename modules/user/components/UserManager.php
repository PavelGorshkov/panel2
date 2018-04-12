<?php

namespace app\modules\user\components;

use Adldap\Models\User as LdapUser;
use app\modules\user\forms\ProfileForm;
use app\modules\user\forms\RegistrationForm;
use app\modules\user\helpers\EmailConfirmStatusHelper;
use app\modules\user\helpers\ModuleTrait;
use app\modules\user\helpers\Password;
use app\modules\user\helpers\RegisterFromHelper;
use app\modules\user\helpers\UserManagerEventHelper;
use app\modules\user\helpers\UserStatusHelper;
use app\modules\user\models\IdentityUser;
use app\modules\user\models\Profile;
use app\modules\user\models\query\UserQuery;
use app\modules\user\models\RegisterUser;
use app\modules\user\models\User;
use app\modules\user\models\Token;
use yii\base\Component;
use yii\db\Expression;
use yii\db\Transaction;
use yii\helpers\Html;
use yii\web\ServerErrorHttpException;

/**
 * Компонент для управления пользовательскими данными
 *
 * Class UserManager
 * @package app\modules\user\components
 */
class UserManager extends Component
{
    const EVENT_CHANGE_EMAIL = 'user.change.email';

    const EVENT_SUCCESS_REGISTRATION = 'user.success.registration';

    const EVENT_SUCCESS_REGISTRATION_NEED_ACTIVATION = 'user.success.registration.need.activation';

    const EVENT_RECOVERY_PASSWORD = 'user.recovery.password';

    const EVENT_GENERATE_PASSWORD = 'user.generate.password';

    const EVENT_CHANGE_PASSWORD = 'user.change.password';


    use ModuleTrait;
    use UserManagerEventHelper;

    /**
     * @var TokenStorage
     */
    protected $tokenStorage;


    /**
     * @var UserQuery
     */
    protected $userQuery;



    /**
     * @param User $user
     * @param string $email
     *
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws \yii\base\Exception
     */
    public function changeEmail(User $user, $email)
    {
        if ($email == $user->email && EmailConfirmStatusHelper::isConfirmedEmail($user)) return true;

        $transaction = app()->db->beginTransaction();

        $user = $this->setUserStatusTypeAndEmailConfirm($user);
        $user->email = $email;

        if (!$user->save()) return $this->failureTransaction($transaction);

        if (!$this->module->emailAccountVerification) {

            user()->setWarningFlash('Вам необходимо продтвердить новый e-mail, проверьте почту!');
            return $this->successTransaction($transaction);
        }

        $token = $this->tokenStorage->createEmailActivationToken($user);

        if ($token === false) return $this->failureTransaction($transaction);

        $this->trigger(self::EVENT_CHANGE_EMAIL, $this->getUserTokenEvent($user, $token));
        user()->setSuccessFlash('Ваш email был изменен');

        return $this->successTransaction($transaction);
    }


    /**
     * @param User $user
     * @param Token $token
     * @param string $password
     *
     * @return bool
     *
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function changePassword(User $user, Token $token, $password)
    {
        $transaction = app()->db->beginTransaction();

        if (!$this->updateUserHashPassword($user, $password)) return $this->failureTransaction($transaction);

        if (!$this->tokenStorage->delete($token)) return $this->failureTransaction($transaction);

        $this->trigger(self::EVENT_CHANGE_PASSWORD, $this->getUserEvent($user));

        $transaction->commit();

        return true;
    }


    /**
     * @param string $password
     *
     * @param null $model
     * @return bool
     * @throws \yii\base\Exception
     */
    public function changePasswordProfile($password, $model = null)
    {
        if ($model === null) {

            $model = app()->user->identity;
        }

        return $this->updateUserHashPassword($model, $password);
    }


    /**
     * @param RegistrationForm $model
     *
     * @return RegisterUser|User
     * @throws \yii\base\Exception
     */
    protected function createUserForRegistration(RegistrationForm $model)
    {
        $user = new RegisterUser();

        $user->setScenario(RegisterUser::SCENARIO_REGISTER);

        $user->setAttributes($model->getAttributes());

        $user = $this->setUserStatusTypeAndEmailConfirm($user);

        $user->hash = Password::hash($model->password);

        $user->registered_from = RegisterFromHelper::FORM;

        return $user;
    }


    /**
     * @param Transaction $transaction
     * @return bool
     *
     * @throws \yii\db\Exception
     */
    protected function failureTransaction(Transaction $transaction)
    {
        $transaction->rollBack();

        return false;
    }


    /**
     * @param string $accountName
     * @param string $password
     * @return IdentityUser|null
     * @throws ServerErrorHttpException
     * @throws \Adldap\AdldapException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function findUserByLdap($accountName, $password)
    {
        /* @var $ldapData LdapUser */
        $ldapData = app()->ldap->getProvider('user_ldap')->search()->users()->where('samaccountname', '=', $accountName)->find($accountName);

        if ($ldapData !== null) {

            $transaction = app()->getDb()->beginTransaction();

            $user = new IdentityUser();
            $user->setAttributes([
                'username' => $ldapData->getAccountName(),
                'email' => $ldapData->getEmail() ? $ldapData->getEmail() : $ldapData->getAccountName() . '@marsu.ru',
                'email_confirm' => EmailConfirmStatusHelper::EMAIL_CONFIRM_YES,
                'hash' => Password::hash($password),
                'status' => UserStatusHelper::STATUS_ACTIVE,
                'registered_from' => RegisterFromHelper::LDAP,
                'access_level' => $this->module->ldapRole,
            ]);

            if (!$user->validate()) {

                $this->failureTransaction($transaction);
                throw new ServerErrorHttpException(Html::errorSummary($user));
            }

            if ($user->save()) {

                $profile = new Profile();
                $profile->setAttributes([
                    'user_id' => $user->getPrimaryKey(),
                    'full_name' => $ldapData->getCommonName(),
                    'department' => $ldapData->getDepartment(),
                    'phone' => $ldapData->getTelephoneNumber(),
                ]);

                if (!$profile->validate()) {

                    $this->failureTransaction($transaction);
                    throw new ServerErrorHttpException(Html::errorSummary($profile));
                }

                $profile->save();

                $this->successTransaction($transaction);
            }

            return $user;
        }

        return null;
    }


    /**
     * @param User $user
     * @param Token $token
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function generatePassword(User $user, Token $token)
    {
        $password = Password::generate(8);

        $transaction = app()->db->beginTransaction();

        if (!$this->updateUserHashPassword($user, $password)) return $this->failureTransaction($transaction);

        $this->trigger(self::EVENT_GENERATE_PASSWORD, $this->getUserPasswordEvent($user, $password));

        if (!$this->tokenStorage->delete($token)) return $this->failureTransaction($transaction);

        return $this->successTransaction($transaction);
    }


    /**
     * @param $token
     * @param $tokenType
     * @return array list(UserToken, User)
     */
    public function getTokenUserList($token, $tokenType)
    {
        $tokenModel = $this->tokenStorage->getToken($token, $tokenType);

        if ($tokenModel === null) return [null, null];

        return [$tokenModel, user()->identity->findIdentity($tokenModel->user_id)];
    }


    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $this->userQuery = User::find();

        /** @var $tokenStorage TokenStorage */
        $tokenStorage = \Yii::createObject(['class' => TokenStorage::class]);

        $this->setTokenStorage($tokenStorage);

        $this->setListener();
    }


    /**
     * @param string $login
     * @param string $password
     * @return bool
     * @throws \Adldap\AdldapException
     * @throws \yii\base\InvalidConfigException
     * @throws ServerErrorHttpException
     */
    public function isAuthLDAP($login, $password)
    {
        return app()->ldap->getProvider('user_ldap')->auth()->attempt($login, $password);
    }


    /**
     * Восстановление пароля, отправка токена смены пароля
     *
     * @param User $user
     *
     * @return bool
     * @throws \yii\db\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\Exception
     */
    public function recoverySendMail(User $user)
    {
        if (null === $user) return false;

        $transaction = app()->getDb()->beginTransaction();

        $token = $this->tokenStorage->createPasswordToken($user);

        if ($token === null) return $this->failureTransaction($transaction);

        $this->trigger(self::EVENT_RECOVERY_PASSWORD, $this->getUserTokenEvent($user, $token));

        return $this->successTransaction($transaction);
    }


    /**
     * Регистрация нового пользователя через Форму
     *
     * @param RegistrationForm $model
     * @return bool
     * @throws \yii\db\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\Exception
     */
    public function registerForm(RegistrationForm $model)
    {
        $user = $this->createUserForRegistration($model);

        $transaction = app()->db->beginTransaction();

        if (!$user->save()) {

            return $this->failureTransaction($transaction);
        } else {

            $profile = new Profile();
            $profile->setAttributes($profile->getAttributes());
            $profile->user_id = $user->getPrimaryKey();

            if (!$profile->save()) return $this->failureTransaction($transaction);
        }

        $user = User::findOne($user->getPrimaryKey());

        if (!$this->module->emailAccountVerification) {

            $this->trigger(
                self::EVENT_SUCCESS_REGISTRATION,
                $this->getUserEvent($user)
            );

        } else {

            $this->trigger(
                self::EVENT_SUCCESS_REGISTRATION_NEED_ACTIVATION,
                $this->getUserTokenEvent($user, $this->tokenStorage->createAccountActivationToken($user))
            );
        }

        return $this->successTransaction($transaction);
    }


    /**
     * @param ProfileForm $model
     *
     * @return bool
     * @throws \yii\db\Exception
     */
    public function saveProfile(ProfileForm $model)
    {
        $data = $model->getAttributes();

        $data['avatar'] = $data['avatar_file'];

        unset($data['avatar_file']);
        unset($data['email']);

        $transaction = app()->db->beginTransaction();

        user()->identity->setAttributes($data);
        user()->profile->setAttributes($data);

        if (user()->identity->save() && user()->profile->save()) {

            $this->successTransaction($transaction);
            return true;
        } else {

            $this->failureTransaction($transaction);
            return false;
        }
    }


    /**
     * @param TokenStorage $tokenStorage
     */
    public function setTokenStorage(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }


    /**
     * @param User $user
     *
     * @return User
     */
    protected function setUserStatusTypeAndEmailConfirm(User $user)
    {
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
    protected function successTransaction(Transaction $transaction)
    {
        $transaction->commit();

        return true;
    }


    /**
     * @param User $user
     * @param $password
     * @return bool
     * @throws \yii\base\Exception
     */
    public function updateUserHashPassword(User $user, $password)
    {
        return (bool)$user->updateAttributes(['hash' => Password::hash($password)]);
    }


    /**
     * @param User $user
     *
     * @return bool
     */
    protected function updateUserSuccessStatus(User $user)
    {
        return (bool)$user->updateAttributes([
            'status' => UserStatusHelper::STATUS_ACTIVE,
            'status_change_at' => new Expression('NOW()'),
            'email_confirm' => EmailConfirmStatusHelper::EMAIL_CONFIRM_YES,
        ]);
    }


    /**
     * Проверка Email
     *
     * @param string $token
     * @param integer $tokenType
     *
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function verifyEmail($token, $tokenType)
    {
        /* @var User $user */
        /* @var Token $tokenModel */
        list($tokenModel, $user) = $this->getTokenUserList($token, $tokenType);

        if ($user === null || $tokenModel === null) return false;

        $transaction = app()->db->beginTransaction();

        if (!$this->updateUserSuccessStatus($user)) return $this->failureTransaction($transaction);

        if (!$this->tokenStorage->delete($tokenModel)) return $this->failureTransaction($transaction);

        return $this->successTransaction($transaction);
    }
}