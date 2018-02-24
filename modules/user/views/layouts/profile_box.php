<?php

/* @var $this \app\modules\core\components\View */
/* @var $content string */

use app\modules\core\widgets\BoxWidget;
use app\modules\user\widgets\InfoProfileWidget;

$this->beginContent('@app/modules/core/views/layouts/admin.php');
?>
<div class="row">
    <div class="col-md-3">
        <?php try {
            echo InfoProfileWidget::widget();
        } catch (Exception $e) {
            echo $e->getMessage();
        } ?>
    </div>
    <div class="col-md-9">
        <? BoxWidget::begin([
            'type' => 'warning',
            'title' => $this->getSmallTitle()
        ]) ?>
        <?= $content ?>
        <? BoxWidget::end() ?>
    </div>
</div>
<?php $this->endContent();