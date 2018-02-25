<?php

namespace app\modules\cron\models\query;

use yii\db\ActiveQuery;
use app\modules\cron\models\Job;

/**
 * This is the ActiveQuery class for [[Job]].
 *
 * @see Job
 */
class JobQuery extends ActiveQuery
{

    /**
     * @inheritdoc
     * @return Job[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }


    /**
     * @inheritdoc
     * @return Job|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    /**
     * Получение списка актуальных расписаний
     * @param null $db
     * @return Job[]|array
     */
    public function active($db = null)
    {
        return $this->where('is_active = 1')->all($db);
    }
}
