<?php

namespace app\modules\progress\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "progress__statistic".
 *
 * @property int $student_id
 * @property int $year
 * @property double $average
 * @property string $created_at
 * @property string $updated_at
 */
class Statistic extends UisActiveRecord{

    /**
     * @inheritdoc
     */
    public static function tableName(){
        return '{{%progress__statistic}}';
    }


    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            [['student_id', 'average', 'year'], 'required'],
            [['student_id', 'year'], 'integer'],
            [['average'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels(){
        return [
            'student_id' => 'Student ID',
            'year' => 'Year',
            'average' => 'Average',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


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
     * @return \yii\db\ActiveQuery
     */
    public function getStudent(){
        return $this->hasOne(Student::class, ['id' => 'student_id']);
    }
}