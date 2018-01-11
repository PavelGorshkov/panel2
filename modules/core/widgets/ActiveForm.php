<?php
namespace app\modules\core\widgets;

use kartik\form\ActiveForm as ParentActiveForm;

/**
 * Class ActiveForm
 * @package app\modules\core\widgets
 */
class ActiveForm extends ParentActiveForm
{
    /**
     * @var bool
     */
    public $calloutVisible = true;

    /**
     * Registers the needed assets
     */
    public function registerAssets()
    {

        $view = $this->getView();

        $view->registerCss('
div.required label.control-label:after, span.required:before
{
    content: " *";
    color: red;
}
');
        parent::registerAssets();
    }


    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     */
    public function init()
    {
        parent::init();

        if ($this->calloutVisible) {

            echo CalloutWidget::widget([
                'message' => 'Поля отмеченные <span class="required"></span> обязательны для заполнения!',
            ]);
        }
    }
}