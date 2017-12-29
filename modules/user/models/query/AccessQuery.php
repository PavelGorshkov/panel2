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
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

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
    public function getData($id, $type) {

        $data = $this
            ->select('access')
            ->where('id = :id AND type = :type', [':id'=>(int) $id, ':type'=>(int) $type])
            ->asArray()
            ->all();

        return array_flip($data);
    }


    public function getDataForUser($id) {

        return $this->getData($id, Access::TYPE_USER);
    }

    public function getDataForRole($id) {

        return $this->getData($id, Access::TYPE_ROLE);
    }
}
