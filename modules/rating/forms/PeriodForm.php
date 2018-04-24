<?php

namespace app\modules\rating\forms;

use app\modules\core\components\FormModel;
use app\modules\core\interfaces\SaveModelInterface;
use \app\modules\rating\models\Period;
use app\modules\rating\helpers\ModuleTrait;
use yii\web\ServerErrorHttpException;
use yii\base\Model;

/**
 * PeriodForm represents the model
 * behind the form of `\app\modules\rating\models\Period`.
 *
 * Class PeriodForm
 * @package app\modules\rating\forms
 */
class PeriodForm extends FormModel implements SaveModelInterface
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
     * @param Model|Period $model
     * @return bool
     */
    public function processingData(Model $model) {

        //TODO реализвать метод сохранения данных

        $model->setAttributes($this->getAttributes());

        if (!$model->validate()) {

            throw new ServerErrorHttpException($model->getErrors());
        }

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
