<?php

namespace app\modules\user\models\query;
use app\modules\user\helpers\UserStatusHelper;

/**
 * This is the ActiveQuery class for [[User]].
 *
 * @see User
 * @method [User] all(\yii\db\Connection $db = null)
 * @method User|ActiveRecord|[]|null one(\yii\db\Connection $db = null)
 *
 */
class UserQuery extends \yii\db\ActiveQuery
{
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\user\models\User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    public function findUser($condition, $params = []) {

        return $this->where($condition, $params);
    }

    public function active() {

        return $this->andWhere(['status'=>UserStatusHelper::STATUS_ACTIVE]);
    }


    public function email($email) {

        return $this->where('email = :email', [':email'=>$email])->active()->one();
    }
}
