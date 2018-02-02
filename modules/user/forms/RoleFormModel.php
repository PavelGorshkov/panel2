<?php

namespace app\modules\user\forms;

use app\modules\core\interfaces\SaveModelInterface;
use yii\base\Model;
use \app\modules\user\models\Role;
use app\modules\core\helpers\ModuleTrait;

/**
 * RoleFormModel represents the model
 * behind the form of `\app\modules\user\models\Role`.
 *
 * Class RoleFormModel
 * @package app\modules\user\forms
 */
class RoleFormModel extends Model implements SaveModelInterface
{
    use ModuleTrait;

    /**
     * @inheritdoc
     * @return array
     */
    public function rules()
    {
        return [

        ];
    }


    /**
     * @param Model|Role $model
     * @return bool
     */
    public function processingData(Model $model) {

        //TODO реализвать метод сохранения данных

        $model->setAttributes($this->getAttributes());

        /*
        // Проверка валидации перед сохранением
        $model->validate();
        printr($model->getErrors(), 1);
        */

        return $model->save();
    }


    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [

        ];
    }
}
