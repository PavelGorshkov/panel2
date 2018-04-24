<?php

use app\modules\core\widgets\BoxWidget;
use yii\web\View;

/* @var $this View */
/* @var $data array */
/* @var $year integer */
/* @var $form string */
?>

<?php if($data): ?>
    <?php BoxWidget::begin(['type'=>BoxWidget::TYPE_PRIMARY, 'title'=>'Статистика по факультетам']); ?>

        <div class="list-group" id="unit_list_group_id">
            <?php foreach($data as $unit => $item): ?>

                <a href="javascript:void(0);" data-year="<?=$year?>" data-form="<?=$form?>" data-unit="<?=$unit?>" class="list-group-item">
                    <?= $this->render('_progress_bar', ['title' => $item['title'], 'sum' => $item['sum'], 'count' => $item['count']]);?>
                </a>

            <?php endforeach; ?>
        </div>

    <?php BoxWidget::end(); ?>
<?php endif; ?>
