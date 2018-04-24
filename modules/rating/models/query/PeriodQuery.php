<?php

namespace app\modules\rating\models\query;

/**
 * This is the ActiveQuery class for [[\app\modules\rating\models\Period]].
 *
 * @see \app\modules\rating\models\Period
 */
class PeriodQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\modules\rating\models\Period[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\rating\models\Period|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
