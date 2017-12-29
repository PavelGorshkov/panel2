<?php
namespace app\modules\user\models\query;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\user\models\UserProfile]].
 *
 * @see \app\modules\user\models\Profile
 */
class ProfileQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\modules\user\models\Profile[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\user\models\Profile|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
