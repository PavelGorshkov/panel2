<?php
namespace app\modules\core\models;

use app\modules\core\models\query\SettingsQuery;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%core_settings}}".
 *
 * @property integer $id
 * @property string $module
 * @property string $param_name
 * @property string $param_value
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 */
class Settings extends \yii\db\ActiveRecord
{
    const USER_DATA = 'user_data';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%core_settings}}';
    }


    public function beforeSave($insert) {

        if ($this->module === self::USER_DATA) {

            $this->user_id  = user()->id;
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
    public static function find() {

        return new SettingsQuery(get_called_class());
    }


    public static function findAllModuleData() {

        $data = cache()->get('find_all_module_data');

        if ($data === false) {

            $temp = self::find()
                ->select(['module', 'param_name', 'param_value'])
                ->modulesData(self::USER_DATA)
                ->asArray()
                ->all();

            $data = [];
            foreach ($temp as $v) {

                $data[$v['module']][$v['param_name']] = $v['param_value'];
            }

            cache()->set('find_all_module_data', $data);
        }

        return $data;
    }


    public static function findAllUserData() {

        $data = cache()->get('find_all_user_data');

        if ($data === false) {

            $temp = self::find()
                ->select(['param_name', 'param_value'])
                ->userData(self::USER_DATA)
                ->asArray()
                ->all();

            $data = ArrayHelper::map($temp, 'param_name', 'param_value');

            cache()->set('find_all_user_data', $data);
        }

        return $data;
    }


    public static function saveUserData($name, $value) {

        $model = self::find()->findUserParam($name, self::USER_DATA)->one();

        if ($model === null) {

            $model = new self;
            $model->module = self::USER_DATA;
            $model->param_name = $name;
        }

        $model->param_value = $value;

        cache()->delete('find_all_user_data');

        return $model->save();
    }


    public static function saveModuleData($module, $data=[]) {

        if (!count($data)) return true;

        $models = self::find()->findAllModuleParam($module)->all();

        foreach ($models as $model) {

            if (isset($data[$model->param_name])) {

                if ($data[$model->param_name] != $model->param_value) {

                    $model->param_value = $data[$model->param_name];
                    $model->save();
                }

                unset($data[$model->param_name]);
            }
        }

        if (count($data)) {

            foreach ($data as $param => $value) {

                $model = new self;
                $model->module = $module;
                $model->param_name = $param;
                $model->param_value = $value;
                $model->user_id = 0;

                $model->save();
            }
        }

        cache()->delete('find_all_module_data');

        return true;
    }
}
