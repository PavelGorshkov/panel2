<?php

namespace app\modules\user\models;

use app\modules\core\components\behaviors\ModelWebUserBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%user_token}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $token
 * @property integer $type
 * @property integer $status
 * @property integer $ip
 * @property string $expire
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property User $user
 */
class UserToken extends \yii\db\ActiveRecord
{
    public function behaviors() {

        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => ModelWebUserBehavior::className(),
                'value' => user()->id?user()->id:0,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_token}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'expire'], 'required'],
            [['user_id', 'type', 'status', 'ip', 'created_by', 'updated_by'], 'integer'],
            [['expire', 'created_at', 'updated_at'], 'safe'],
            [['token'], 'string', 'max' => 32],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'token' => 'Токен',
            'type' => 'Тип',
            'status' => 'Статус',
            'ip' => 'IP адрес',
            'expire' => 'Истекает',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return \app\modules\user\models\query\UserTokenQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\user\models\query\UserTokenQuery(get_called_class());
    }
}
