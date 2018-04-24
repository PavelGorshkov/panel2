<?php

use app\modules\core\components\View;
use app\modules\core\widgets\BoxBodyWidget;
use app\modules\finance\helpers\Dictionary;
use app\modules\finance\models\Balance;
use yii\helpers\ArrayHelper;

/* @var $this View */
/* @var $model Balance */

$data = $model->getDataForLastDay();

$begin = count($data)?array_sum(ArrayHelper::getColumn($data, 'begin')):'-';
$end = count($data)?array_sum(ArrayHelper::getColumn($data,'end')):'-';

BoxBodyWidget::begin();
?>
<div class="row">
    <div class="col-xs-12">
        <table class="table table-bordered">
            <thead>
            <tr class="bg-green">
                <th><h4>Финансовые остатки на <?=$model->getLastDay()?></h4></th>
                <th class="text-center"><b>Остатки на начало дня</b><br><?=app()->formatter->asCurrency($begin)?></th>
                <th class="text-center"><b>Остатки на конец дня</b><br><?=app()->formatter->asCurrency($end)?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data as $row):?>
                <tr>
                    <th><?= Dictionary::getItem(Dictionary::DICTIONARY_KVD, $row['kvd_id'])['value']?></th>
                    <td class="text-center"><?= app()->formatter->asCurrency($row['begin'])?></td>
                    <td class="text-center"><?= app()->formatter->asCurrency($row['end'])?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
BoxBodyWidget::end();