<?php

namespace app\modules\user\models;

use app\modules\core\components\behaviors\ModelWebUserBehavior;
use app\modules\user\helpers\UserAccessLevelHelper;
use app\modules\user\models\query\RoleQuery;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%user_role}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property-read User $users
 * @property-read Access $access
 */
class Role extends ActiveRecord
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => ModelWebUserBehavior::class,
                'value' => user()->id ? user()->id : 0,
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user__role}}';
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers() {

        return $this->hasMany(User::class, ['access_level'=>'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccess() {

        return $this->hasMany(Access::class, ['id'=>'id', 'type'=>Access::TYPE_ROLE]);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 50],
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
            'title' => 'Название',
            'description' => 'Описание',
            'created_at' => 'Создан',
            'updated_at' => 'Изменен',
        ];
    }


    /**
     * @inheritdoc
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        app()->cache->flush();
    }


    /**
     * @inheritdoc
     * @return bool
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {

            User::updateAll(['access_level'=>UserAccessLevelHelper::LEVEL_USER], ['access_level'=>$this->id]);

            return true;
        }

        return false;
    }


    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        Access::deleteAll(['id'=>$this->id, 'type'=>Access::TYPE_ROLE]);

        parent::afterDelete();
    }


    /**
    * @return array
    */
    public static function getList()
    {
        return ArrayHelper::map(
            self::find()
                ->select('id, title')
                ->asArray()
                ->all(),
            'id',
            'title'
        );
    }
}
