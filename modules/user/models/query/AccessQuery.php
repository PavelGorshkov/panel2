<?php

namespace app\modules\user\models\query;

use app\modules\user\models\Access;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\user\models\UserAccess]].
 *
 * @see \app\modules\user\models\Access
 */
class AccessQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\modules\user\models\Access[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }


    /**
     * @inheritdoc
     * @return \app\modules\user\models\Access|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    /**
     * @param $id
     * @param $type
     * @return array|null
     */
    public function getData($id, $type)
    {
        $data = $this
            ->select(['access'])
            ->where(['id' => (int)$id, 'type' => (int)$type])
            ->asArray()
            ->column();

        return array_flip($data);
    }


    /**
     * @param $id
     * @return array|null
     */
    public function getDataForUser($id)
    {
        return $this->getData($id, Access::TYPE_USER);
    }


    /**
     * @param $id
     * @return array|null
     */
    public function getDataForRole($id)
    {
        return $this->getData($id, Access::TYPE_ROLE);
    }
}
