<?php

namespace app\modules\finance\models;

use app\modules\core\components\UisActiveRecord;
use app\modules\finance\interfaces\RangeDateInterface;


/**
 * Class Finance
 * @package app\modules\finance\models
 */
abstract class Finance extends UisActiveRecord implements RangeDateInterface
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