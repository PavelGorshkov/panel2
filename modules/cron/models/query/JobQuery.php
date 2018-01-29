<?php

namespace app\modules\cron\models\query;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\cron\models\Job]].
 *
 * @see \app\modules\cron\models\Job
 */
class JobQuery extends ActiveQuery{

    /**
     * @inheritdoc
     * @return \app\modules\cron\models\Job[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\cron\models\Job|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
