<?php

namespace app\modules\finance\models;

/**
 * Class Finance
 * @package app\modules\finance\models
 */
abstract class Finance extends UisActiveRecord
{
    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        parent::beforeSave($insert);

        return false;
    }
}