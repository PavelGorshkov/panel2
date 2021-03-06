<?php

namespace app\modules\finance\models\dictionary;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%finance_kbk}}".
 *
 * @property string $full_value
 */
class Kbk extends DictionaryBase
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance__dict_kbk}}';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['value', 'parent_uid', 'full_value', 'created_at', 'updated_at'], 'safe'],
            [['full_value'], 'string', 'max' => 500],
        ];

        return ArrayHelper::merge(parent::rules(), $rules);
    }
}
