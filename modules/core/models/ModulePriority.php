<?php
namespace app\modules\core\models;

use yii\helpers\ArrayHelper;

/**
 * Class ModulePriority
 * @package app\modules\core\models
 *
 * @property integer $id
 * @property string $module
 * @property string $param_name
 * @property string $param_value
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 *
 */
class ModulePriority extends Settings
{
    const PARAM = 'priority';

    /**
     * @return array|mixed
     */
    public static function findAllData()
    {
        $data = cache()->get('find_all_priority_data');

        if ($data === false) {

            $temp = self::find()
                ->select(['module', 'param_value'])
                ->andWhere(['not', ['module'=>self::USER_DATA]])
                ->andWhere(['param_name' => self::PARAM])
                ->asArray()
                ->all();

            $data = ArrayHelper::map($temp, 'module', 'param_value');

            cache()->set('find_all_priority_data', $data, 3600);
        }

        return $data;
    }


    /**
     * @return array
     */
    public static function findModels()
    {
        $models = self::find()
            ->andWhere('`module` != :module', [':module'=>self::USER_DATA])
            ->andWhere(['param_name' => self::PARAM])
            ->all();

        $data = [];

        foreach ($models as $m) {

            $data[$m->module] = $m;
        }

        return $data;
    }


    /**
     * @param array $data
     * @throws \yii\base\InvalidConfigException
     */
    public static function saveData(array $data) {

        $models = self::findModels();

        foreach ($data as $m => $priority) {

            if (isset($models[$m])) {

                $model = $models[$m];

                if ($model->param_value == $priority) {continue;}

            } else {

                $model = \Yii::createObject([
                    'class'=>self::class,
                    'module' => $m,
                    'param_name' => self::PARAM,
                    'user_id' => 0
                ]);
            }

            $model->param_value = (string) $priority;

            $model->save();
        }
    }
}