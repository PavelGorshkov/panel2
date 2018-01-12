<?php

namespace app\modules\user\models\query;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the ActiveQuery class for [[\app\modules\user\models\UserRole]].
 *
 * @see \app\modules\user\models\Role
 */
class RoleQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\modules\user\models\Role[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\user\models\Role|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    /**
     * @return array
     */
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
