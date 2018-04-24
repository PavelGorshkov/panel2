<?php

namespace app\modules\rating\models;

use Yii;

/**
 * This is the model class for table "rating__list__value".
 *
 * @property int $id
 * @property string $title
 * @property int $list_id
 * @property int $points
 * @property double $weight
 * @property string $classname
 * @property string $created_at
 * @property string $updated_at
 */
class ListValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rating__list__value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['list_id', 'points'], 'integer'],
            [['weight'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'classname'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'list_id' => 'List ID',
            'points' => 'Points',
            'weight' => 'Weight',
            'classname' => 'Classname',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @inheritdoc
     * @return \app\modules\rating\models\query\ListValueQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\rating\models\query\ListValueQuery(get_called_class());
    }
}
