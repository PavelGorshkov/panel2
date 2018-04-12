<?php
namespace app\modules\core\models;

use app\modules\core\models\query\SettingsQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%core__settings}}".
 *
 * @property integer $id
 * @property string $module
 * @property string $param_name
 * @property string $param_value
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 */
class Settings extends ActiveRecord
{
    const USER_DATA = 'user_data';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%core__settings}}';
    }


    /**
     * @inheritdoc
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->module === self::USER_DATA) {

            $this->user_id = user()->id;
        }

        if (user()->isGuest) return false;

        return parent::beforeSave($insert);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['param_name', 'param_value'], 'required'],
            [['user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['module'], 'string', 'max' => 50],
            [['param_name'], 'string', 'max' => 100],
            [['param_value'], 'string', 'max' => 500],
            [['module', 'param_name', 'user_id'], 'unique', 'targetAttribute' => ['module', 'param_name', 'user_id'], 'message' => 'The combination of Module, Param Name and User ID has already been taken.'],
        ];
    }

    /**
     * @return SettingsQuery
     */
    public static function find()
    {
        return new SettingsQuery(get_called_class());
    }
}
