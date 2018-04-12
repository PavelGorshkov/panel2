<?php

use app\modules\developer\generators\crud\Generator;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$formModelClass = StringHelper::basename($generator->formModelClass);

if ($modelClass === $formModelClass) {
    $modelAlias = $modelClass . 'Model';
}

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->formModelClass, '\\')) ?>;

use app\modules\core\components\FormModel;
use app\modules\core\interfaces\SaveModelInterface;
use <?= $generator->modelClass ?>;
use app\modules\<?= $generator->module ?>\helpers\ModuleTrait;
use yii\web\ServerErrorHttpException;
use yii\base\Model;

/**
 * <?= $formModelClass ?> represents the model
 * behind the form of `<?= $generator->modelClass ?>`.
 *
 * Class <?= $formModelClass . "\n" ?>
 * @package <?= StringHelper::dirname(ltrim($generator->formModelClass, '\\')) . "\n" ?>
 */
class <?= $formModelClass ?> extends FormModel implements SaveModelInterface
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
     * @param Model|<?= isset($modelAlias) ? $modelAlias : $modelClass ?> $model
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
