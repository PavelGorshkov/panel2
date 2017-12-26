<?php
namespace app\modules\core\models\query;

use app\modules\user\models\UserAccess;
use yii\db\ActiveQuery;

/**
 * Class SettingsQuery
 * @package app\modules\core\models\query
 */
class SettingsQuery extends ActiveQuery
{

    /**
     * @inheritdoc
     * @return UserAccess[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserAccess|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    /**
     * @param $module
     * @return $this
     */
    public function modulesData($module) {

        return $this->where('module != :module',[':module' => $module]);
    }


    /**
     * @param $module
     * @return $this
     */
    public function userData($module) {

        return $this->where('module = :module and user_id = :user', [':module' => $module, ':user' => (int)user()->id]);
    }


    /**
     * @param $name
     * @param $module
     * @return $this
     */
    public function findUserParam($name, $module) {

        return
            $this->andWhere(
                'module=:module AND param_name=:name AND user_id=:user', [
                    ':module' => $module,
                    ':name' => $name,
                    ':user' => user()->id
                ]
            );
    }


    public function findAllModuleParam($module) {

        return
            $this->andWhere(
                'module = :module',
                [':module'=>$module]
            );
    }

}