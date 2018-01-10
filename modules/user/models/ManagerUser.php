<?php
namespace app\modules\user\models;


use app\modules\user\helpers\UserAccessLevelHelper;
use app\modules\user\helpers\UserStatusHelper;

class ManagerUser extends User
{
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            return $this->isSaveUpdate();
        }

        return false;
    }


    /**
     * @return bool
     */
    protected function isSaveUpdate() {

        if ($this->isNewRecord) return true;

        if (UserStatusHelper::STATUS_ACTIVE !== $this->getAttribute('status')) {

            return !$this->isAdmin();
        }

        if (
            $this->getOldAttribute('access_level') === UserAccessLevelHelper::LEVEL_ADMIN
         && $this->getAttribute('access_level') !== UserAccessLevelHelper::LEVEL_ADMIN
         && $this->id === user()->info->id
        ) {

            return false;
        }

        return true;
    }
}