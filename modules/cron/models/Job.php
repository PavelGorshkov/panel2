<?php
namespace app\modules\cron\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\modules\cron\models\query\JobQuery;
use yii\db\Expression;

/**
 * This is the model class for table "cron_job".
 *
 * @property int $id
 * @property string $command
 * @property int $is_active
 * @property string $params
 * @property string $created_at
 * @property string $updated_at
 */
class Job extends ActiveRecord
{
    /**
     * @return array
     */
    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public static function tableName(){
        return '{{%cron_job}}';
    }


    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            [['command', 'params'], 'required'],
            [['is_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['command', 'params'], 'string', 'max' => 255],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels(){
        return [
            'id' => 'ID',
            'command' => 'Команда запуска',
            'params' => 'Параметры запуска',
            'is_active' => 'Активность',
        ];
    }


    /**
     * @inheritdoc
     * @return JobQuery the active query used by this AR class.
     */
    public static function find(){
        
        return new JobQuery(get_called_class());
    }
}
