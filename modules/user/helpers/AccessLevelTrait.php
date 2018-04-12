<?php

namespace app\modules\user\helpers;

use app\modules\user\models\Access;
use app\modules\user\models\RoleAccess;
use yii\helpers\ArrayHelper;

/**
 * Trait AccessLevelTrait
 * @package app\modules\user\helpers
 */
trait AccessLevelTrait
{
    /**
     * @return int
     */
    public function getAccessLevel()
    {
        return $this->access_level??null;
    }


    /**
     * @return array
     */
    public function getAccessPermissions()
    {
        $data = Access::getData($this->id);

        if ($this->isUFAccessLevel()) {

            $data = ArrayHelper::merge($data, RoleAccess::getData(($this->getAccessLevel())));
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function isUFAccessLevel()
    {
        return $this->getAccessLevel() >= 100;
    }
}