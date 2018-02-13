<?php
namespace app\modules\core\models;

use Yii;


/**
 * Class ModuleSettings
 * @package app\modules\core\models
 */
class ModuleSettings extends Settings
{
    /**
     * @inheritdoc
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert) {

        if (user()->isGuest) return false;

        return parent::beforeSave($insert);
    }


    /**
     * @return array|bool|mixed
     */
    public static function findAllData() {

        $data = cache()->get('find_all_module_data');

        if ($data === false) {

            $temp = self::find()
                ->select(['module', 'param_name', 'param_value'])
                ->where('module != :module',[':module' => self::USER_DATA])
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


    /**
     * @param $module
     * @param array $data
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function saveData($module, $data=[]) {

        if (!count($data)) return true;

        $models = self::findAll(['module'=>$module]);

        foreach ($models as $model) {

            /* @var $model Settings */
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

                $model = Yii::createObject([
                    'class'=>self::className(),
                    'module' => $module,
                    'param_name' => $param,
                    'param_value' => $value,
                    'user_id' => 0
                ]);

                $model->save();
            }
        }

        cache()->delete('find_all_module_data');

        return true;
    }


    /**
     * @param string $module
     * @return int
     */
    public static function deleteData($module)
    {
        return self::deleteAll(['module'=>$module]);
    }
}