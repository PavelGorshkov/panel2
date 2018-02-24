<?php
namespace app\modules\core\models;

use yii\helpers\ArrayHelper;

/**
 * Class UserSettings
 * @package app\modules\core\
 *
 * @property integer $id
 * @property string $module
 * @property string $param_name
 * @property string $param_value
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 */
class UserSettings extends Settings
{
    /**
     * @return array|mixed
     */
    public static function findAllData()
    {
        $data = cache()->get('find_all_user_data');

        if ($data === false) {

            $temp = self::find()
                ->select(['param_name', 'param_value'])
                ->where(['module' => self::USER_DATA, 'user_id' => (int)user()->id])
                ->asArray()
                ->all();

            $data = ArrayHelper::map($temp, 'param_name', 'param_value');

            cache()->set('find_all_user_data', $data, 3600);
        }

        return $data;
    }


    /**
     * @param string $name
     * @param string $value
     * @return bool
     */
    public static function saveData($name, $value)
    {
        $model = self::findOne([
            'module' => self::USER_DATA,
            'param_name' => $name,
            'user_id' => user()->id]);

        if ($model === null) {

            $model = new self;
            $model->module = self::USER_DATA;
            $model->param_name = $name;
        }

        $model->param_value = $value;

        cache()->delete('find_all_user_data');

        return $model->save();
    }


    /**
     * @return int
     */
    public static function deleteAllData()
    {
        return self::deleteAll([
            'module' => self::USER_DATA,
            'user_id' => app()->user->identity->id
        ]);
    }
}