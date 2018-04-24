<?php

namespace app\modules\rating\models;

use Yii;

/**
 * This is the model class for table "rating__plan".
 *
 * @property int $id
 * @property int $period_id
 * @property int $section_id
 * @property int $indicator_id
 * @property int $subindicator_id
 * @property int $points
 * @property double $weight
 * @property string $created_at
 * @property string $updated_at
 */
class Plan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rating__plan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['period_id'], 'required'],
            [['period_id', 'section_id', 'indicator_id', 'subindicator_id', 'points'], 'integer'],
            [['weight'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'period_id' => 'Period ID',
            'section_id' => 'Section ID',
            'indicator_id' => 'Indicator ID',
            'subindicator_id' => 'Subindicator ID',
            'points' => 'Points',
            'weight' => 'Weight',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @inheritdoc
     * @return \app\modules\rating\models\query\PlanQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\rating\models\query\PlanQuery(get_called_class());
    }
}
