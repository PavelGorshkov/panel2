<?php

namespace app\modules\rating\models;

use Yii;

/**
 * This is the model class for table "rating__section".
 *
 * @property int $id
 * @property string $title
 * @property string $created_at
 * @property string $updated_at
 */
class Section extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rating__section';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
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
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @inheritdoc
     * @return \app\modules\rating\models\query\SectionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\rating\models\query\SectionQuery(get_called_class());
    }
}
