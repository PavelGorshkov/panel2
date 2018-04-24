<?php

namespace app\modules\progress\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "progress__student".
 *
 * @property int $id
 * @property string $uid
 * @property string $number
 * @property string $name
 * @property string $surname
 * @property string $patronymic
 * @property string $faculty
 * @property string $group
 * @property string $subgroup
 * @property string $form
 * @property string $status
 * @property string $speciality
 * @property int $course
 * @property int $year
 * @property string $created_at
 * @property string $updated_at
 */
class Student extends UisActiveRecord {

    /**
     * @return string|string[]
     */
    public static function primaryKey(){
        return 'id';
    }


    /**
     * @inheritdoc
     */
    public static function tableName(){
        return '{{%progress__student}}';
    }


    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            [['id', 'uid', 'number', 'name', 'surname', 'faculty', 'group', 'subgroup', 'form', 'status', 'speciality'], 'required'],
            [['id', 'course', 'year'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['uid', 'name', 'surname', 'faculty', 'group', 'subgroup', 'form', 'status', 'speciality'], 'string', 'max' => 40],
            [['number'], 'string', 'max' => 10],
            [['patronymic'], 'string', 'max' => 50],
            [['id'], 'unique'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels(){
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'number' => 'Number',
            'name' => 'Name',
            'surname' => 'Surname',
            'patronymic' => 'Patronymic',
            'faculty' => 'Faculty',
            'group' => 'Group',
            'subgroup' => 'Subgroup',
            'form' => 'Form',
            'status' => 'Status',
            'speciality' => 'Speciality',
            'course' => 'Course',
            'year' => 'Year',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At'
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
    public function getStatistic(){
        return $this->hasOne(Statistic::class, ['student_id' => 'id']);
    }
}
