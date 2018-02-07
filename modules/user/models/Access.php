<?php

namespace app\modules\user\models;

use app\modules\user\models\query\AccessQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_access}}".
 *
 * @property string $access
 * @property integer $type
 * @property integer $id
 */
class Access extends ActiveRecord
{
    const TYPE_USER = 0;

    const TYPE_ROLE = 1;

    const TYPE = self::TYPE_USER;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_access}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['access', 'type', 'id'], 'required'],
            [['type', 'id'], 'integer'],
            [['access'], 'string', 'max' => 100],
            [['access', 'type', 'id'], 'unique', 'targetAttribute' => ['access', 'type', 'id'], 'message' => 'The combination of Access, Type and ID has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'access' => 'Access',
            'type' => 'Type',
            'id' => 'ID',
        ];
    }


    /**
     * @inheritdoc
     * @return AccessQuery
     */
    public static function find()
    {
        return new AccessQuery(get_called_class());
    }


    /**
     * @param int $id
     * @return array|null
     */
    public static function getData($id)
    {
        $data = self::find()
            ->select(['access'])
            ->andWhere(['id' => (int)$id, 'type' => self::TYPE])
            ->asArray()
            ->column();

        return array_flip($data);
    }


    public static function setData($id, $post)
    {
        self::deleteData($id);

        printr($post, 1);
    }


    public static function deleteData($id)
    {
        return self::deleteAll([
            'id'=>$id,
            'type'=>self::TYPE
        ]);
    }
}
