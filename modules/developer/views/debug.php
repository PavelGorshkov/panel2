<?php

use app\modules\core\components\View;
use app\modules\core\widgets\BoxBodyWidget;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var View $view */

$this->setTitle(app()->controller->id);
$this->setSmallTitle(app()->controller->action->id);

BoxBodyWidget::begin([]);

echo Html::a('Вернуться', Url::to(app()->request->getReferrer()), ['class'=>'btn btn-sm btn-primary']);

BoxBodyWidget::end();