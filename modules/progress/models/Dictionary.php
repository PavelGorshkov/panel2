<?php

namespace app\modules\progress\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "progress_dictionary".
 *
 * @property string $uid
 * @property string $dictionary
 * @property string $code
 * @property string $value
 * @property string $parent_uid
 * @property int $catalog
 * @property string $created_at
 * @property string $updated_at
 */
class Dictionary extends UisActiveRecord{

    const EMPTY_UID = '00000000-0000-0000-0000-000000000000';

    const DICTIONARY_FACULTY = 'faculty';
    const DICTIONARY_GROUP = 'group';
    const DICTIONARY_SUBGROUP = 'subgroup';
    const DICTIONARY_FORM = 'form';
    const DICTIONARY_STATUS = 'status';
    const DICTIONARY_PERSON = 'person';
    const DICTIONARY_PERIOD = 'period';
    const DICTIONARY_CONTROL = 'control';
    const DICTIONARY_DISCIPLINE = 'discipline';
    const DICTIONARY_MARK = 'mark';
    const DICTIONARY_SPECIALITY = 'speciality';


    /**
     * Получение списка названий всех справочников
     * @return array
     */
    public static function getDictionaryList(){
        return [
            self::DICTIONARY_FACULTY,
            self::DICTIONARY_GROUP,
            self::DICTIONARY_SUBGROUP,
            self::DICTIONARY_FORM,
            self::DICTIONARY_PERSON,
            self::DICTIONARY_PERIOD,
            self::DICTIONARY_CONTROL,
            self::DICTIONARY_DISCIPLINE,
            self::DICTIONARY_MARK,
            self::DICTIONARY_SPECIALITY,
            self::DICTIONARY_STATUS
        ];
    }


    /**
     * @return array|string[]
     */
    public static function primaryKey(){
        return ['uid', 'dictionary'];
    }


    /**
     * @inheritdoc
     */
    public static function tableName(){
        return '{{%progress__dictionary}}';
    }


    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            [['uid', 'dictionary', 'value'], 'required'],
            [['catalog'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['uid', 'parent_uid'], 'string', 'max' => 50],
            [['dictionary'], 'string', 'max' => 20],
            [['code'], 'string', 'max' => 10],
            [['value'], 'string', 'max' => 255],
            [['uid', 'dictionary'], 'unique', 'targetAttribute' => ['uid', 'dictionary']],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels(){
        return [
            'uid' => 'Uid',
            'dictionary' => 'Dictionary',
            'code' => 'Code',
            'value' => 'Value',
            'parent_uid' => 'Parent Uid',
            'catalog' => 'Catalog',
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
     * @return bool
     */
    public function beforeValidate(){
        if(!$this->parent_uid){
            $this->parent_uid = self::EMPTY_UID;
        }

        return parent::beforeValidate();
    }
}
