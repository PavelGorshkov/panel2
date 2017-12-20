<?php
namespace app\modules\user\components;

use app\modules\user\helpers\ModuleTrait;
use app\modules\user\models\query\UserQuery;
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
}