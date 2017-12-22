<?php

namespace app\modules\user\models\query;

/**
 * This is the ActiveQuery class for [[\app\modules\user\models\UserRole]].
 *
 * @see \app\modules\user\models\UserRole
 */
class UserRoleQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\modules\user\models\UserRole[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\user\models\UserRole|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
