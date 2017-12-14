<?php

namespace app\modules\user\models\query;

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
    public function findUser($condition, $params = []) {

        return $this->where($condition, $params);
    }
}
