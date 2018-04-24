<?php

use app\modules\core\widgets\BoxWidget;
use yii\web\View;

/* @var $this View */
/* @var $data array */
/* @var $year integer */
/* @var $form string */
/* @var $unit string */
?>

<?php BoxWidget::begin(['type'=>BoxWidget::TYPE_PRIMARY, 'title'=>'Статистика по группам']); ?>
    <?php if($data): ?>

        <?php foreach($data as $speciality => $specialityData): ?>

            <h4><?=$specialityData['title']?></h4>

            <!-- Отрисовка аккордеона  -->
            <?php $panelId = $speciality; ?>
            <div class="panel-group" id="<?=$panelId?>" role="tablist" aria-multiselectable="true">

                <?php foreach($specialityData['groups'] as $group => $groupData): ?>

                    <?php $headingId = crc32($panelId.$group); $collapseId = 'collapse'.$headingId; ?>

                    <div class="panel panel-default">

                        <!-- Отрисовка общих данных группы  -->
                        <div class="panel-heading" role="tab" id="<?=$headingId?>">
                            <h4 class="panel-title">
                                <a class="collapsed" role="button" data-toggle="collapse" data-parent="<?='#'.$panelId?>" href="<?='#'.$collapseId?>" aria-expanded="false" aria-controls="<?=$collapseId?>">
                                    <?= $this->render('_progress_bar', ['title' => $groupData['title'], 'sum' => $groupData['sum'], 'count' => $groupData['count']]);?>
                                </a>
                            </h4>
                        </div>

                        <!-- Отрисовка данных по курсам. Выезжающая хрень -->
                        <div id="<?=$collapseId?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="<?=$headingId?>">
                            <div class="panel-body">
                                <?php foreach($groupData['courses'] as $course => $courseData): ?>
                                    <?= $this->render('_progress_bar', ['title' => 'Курс '.$course, 'sum' => $courseData['sum'], 'count' => $courseData['count']]);?>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div>

                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
        <div class="alert alert-warning">Нет данных!</div>
    <?php endif; ?>
<?php BoxWidget::end(); ?>
