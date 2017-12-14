<?php
/**
 * Created by PhpStorm.
 * User: pastet
 * Date: 14.12.2017
 * Time: 10:23
 */

namespace app\modules\core\models\query;

use yii\db\ActiveQuery;

class SettingsQuery extends ActiveQuery
{
    public function modulesData($module) {

        return $this->where('module != :module',[':module' => $module]);
    }

    public function userData($module) {

        return $this->where('module = :module and user_id = :user', [':module' => $module, ':user' => (int)user()->id]);
    }

    public function findUserParam($name, $module) {

        return
            $this->andWhere(
                'module=:module AND param_name=:name AND user_id=:user',
                [':name'=>$name, ':module'=>$module, ':user'=>user()->id]
            );
    }


    public function findAllModuleParam($module) {

        return
            $this->addWhere(
                'module = :module',
                [':module'=>$module]
            );
    }

}