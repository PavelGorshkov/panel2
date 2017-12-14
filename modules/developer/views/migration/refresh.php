<?php
/* @var $this app\modules\core\components\View */
use app\modules\core\widgets\BoxWidget;
use yii\helpers\Html;

/* @var $logs string */
/* @var $module string */
$this->setSmallTitle('Обновление БД');
$this->setTitle($module);

$this->setBreadcrumbs([
    [
        'label' => $this->getTitle(),
        'url'=>['index'],
        'encode' => false,
    ],
    [
        'label' => $this->getSmallTitle(),
        'encode' => false,
    ],
]);

BoxWidget::begin([
    'title'=>$this->getSmallTitle()
])
?>
    <p><?=$logs?$logs:'БД модуля актуальна на текущий момент!'?></p>
    <p><?=Html::a('<i class="fa fa-reply"></i> Вернуться', ['index', 'module'=>$module], ['class'=>'btn btn-sm btn-success'])?></p>
<?php
BoxWidget::end();


