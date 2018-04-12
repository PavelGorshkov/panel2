<?php

namespace app\modules\finance\models\dictionary;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%finance_activity}}".
 *
 * @property string $unit
 */
class Activity extends DictionaryBase
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance__dict_activity}}';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['value', 'parent_uid', 'unit', 'created_at', 'updated_at'], 'safe'],
            [['unit'], 'string', 'max' => 500],
        ];

        return ArrayHelper::merge(parent::rules(), $rules);
    }
}
