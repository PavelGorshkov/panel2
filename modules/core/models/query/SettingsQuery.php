<?php
namespace app\modules\core\models\query;

use app\modules\user\models\Access;
use yii\db\ActiveQuery;

/**
 * Class SettingsQuery
 * @package app\modules\core\models\query
 * @see \app\modules\core\models\Settings
 */
class SettingsQuery extends ActiveQuery
{

    /**
     * @inheritdoc
     * @return Access[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Access|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}