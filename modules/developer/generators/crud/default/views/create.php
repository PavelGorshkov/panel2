<?php

use app\modules\developer\generators\crud\Generator;
use yii\helpers\StringHelper;

/** @var $this yii\web\View */
/** @var $generator Generator */

$modelFormClass = StringHelper::basename($generator->formModelClass);

echo "<?php\n";
?>

/** @var $this View */
/** @var $model <?= $modelFormClass ?> */
/** @var $module Module */

use app\modules\core\components\View;
use app\modules\core\widgets\BoxWidget;
use <?= ltrim($generator->formModelClass, '\\'); ?>;
use app\modules\<?= $generator->module?>\Module;

//$this->setSmallTitle('Title');

BoxWidget::begin([
    'type'=>BoxWidget::TYPE_SUCCESS,
    'title'=>$this->getSmallTitle()
]);
    echo $this->render('_form', [
        'model'=>$model,
        'module'=>$module,
]);
BoxWidget::end();
