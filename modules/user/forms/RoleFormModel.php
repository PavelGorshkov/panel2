<?php

namespace app\modules\user\forms;

use app\modules\core\components\FormModel;
use app\modules\core\interfaces\SaveModelInterface;
use \app\modules\user\models\Role;
use app\modules\core\helpers\ModuleTrait;
use yii\base\Model;

/**
 * RoleFormModel represents the model
 * behind the form of `\app\modules\user\models\Role`.
 *
 * Class RoleFormModel
 * @package app\modules\user\forms
 */
class RoleFormModel extends FormModel implements SaveModelInterface
{
    use ModuleTrait;

    public $title;

    public $description;

    public $id;

    /**
     * @inheritdoc
     * @return array
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 50],
        ];
    }


    public function attributeLabels() {

        return [
            'title'=>'Название',
            'description'=>'Описание',
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
}
