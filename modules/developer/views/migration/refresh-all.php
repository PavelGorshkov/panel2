<?php

use app\modules\core\widgets\BoxWidget;
use yii\helpers\Html;

/* @var $this app\modules\core\components\View */
/* @var $logs string */

$this->setSmallTitle('Обновление БД');
$this->setTitle('Все модули');

$logs = app()->migrator->getHtml();

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
]);
?>
<?=$logs?$logs:Html::tag('p', 'БД системы актуальна на текущий момент!', ['class'=>'text text-success'])?>
<div><?=Html::a('<i class="fa fa-reply"></i> Вернуться', ['index'], ['class'=>'btn btn-sm btn-success'])?></div>
<?php
BoxWidget::end();
