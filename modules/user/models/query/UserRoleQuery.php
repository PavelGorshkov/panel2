<?php

namespace app\modules\user\models\query;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the ActiveQuery class for [[\app\modules\user\models\UserRole]].
 *
 * @see \app\modules\user\models\UserRole
 */
class UserRoleQuery extends ActiveQuery
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


    public function allListRoles() {

        return ArrayHelper::map(
            $this
                ->select('id, title')
                ->asArray()
                ->all(),
            'id',
            'title'
        );
    }

}
