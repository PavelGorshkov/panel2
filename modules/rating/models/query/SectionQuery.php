<?php

namespace app\modules\rating\models\query;

/**
 * This is the ActiveQuery class for [[\app\modules\rating\models\Section]].
 *
 * @see \app\modules\rating\models\Section
 */
class SectionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \app\modules\rating\models\Section[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\rating\models\Section|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
