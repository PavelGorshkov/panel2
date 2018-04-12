<?php

namespace app\modules\finance\models\dictionary;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%finance_classifier}}".
 */
class Classifier extends DictionaryBase
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance__dict_classifier}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [[['value', 'parent_uid', 'created_at', 'updated_at'], 'safe']]);
    }
}
