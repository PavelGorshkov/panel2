<?php

namespace app\modules\rating\models;

use Yii;

/**
 * This is the model class for table "rating__list".
 *
 * @property int $id
 * @property string $title
 * @property int $is_const
 * @property int $period_id
 * @property int $type
 * @property string $created_at
 * @property string $updated_at
 */
class ListName extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rating__list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['period_id', 'type'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['is_const'], 'string', 'max' => 1],
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
            'is_const' => 'Is Const',
            'period_id' => 'Period ID',
            'type' => 'Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @inheritdoc
     * @return \app\modules\rating\models\query\ListNameQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\rating\models\query\ListNameQuery(get_called_class());
    }
}
