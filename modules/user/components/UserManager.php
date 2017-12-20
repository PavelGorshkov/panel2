<?php
namespace app\modules\user\components;

use app\modules\user\helpers\ModuleTrait;
use app\modules\user\models\ProfileRegistrationForm;
use app\modules\user\models\query\UserQuery;
use app\modules\user\models\RegistrationForm;
use app\modules\user\models\User;
use yii\base\Component;

/**
 * Компонент для управления пользовательскими данными
 *
 * Class UserManager
 * @package app\modules\user\components
 */
class UserManager extends Component {

    use ModuleTrait;

    /**
     * @var UserQuery
     */
    protected $userQuery;


    public function init() {

        $this->userQuery = User::find();
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

        $user = new User();
        $user->setScenario('register');

        $user->setAttributes($model);

        $transaction  = app()->db->beginTransaction();

        try {

            printr($user, 1);

            $transaction->commit();

            return true;

        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::warning($e->getMessage());
            throw $e;
        }
    }
}