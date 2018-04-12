<?php

namespace app\modules\user\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user__profile}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string $full_name
 * @property string $department
 * @property string $phone
 * @property string $created_at
 * @property string $updated_at
 *
 * @property-read User $info
 */
class Profile extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user__profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['full_name', 'department'], 'string', 'max' => 150],
            [['phone'], 'string', 'max' => 30],
            [['user_id'], 'unique'],

            [['department'], 'string'],

            [['full_name'], 'string', 'max' => 150],

            [['phone'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'full_name' => 'Full Name',
            'department' => 'Department',
            'phone' => 'Phone',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfo()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
