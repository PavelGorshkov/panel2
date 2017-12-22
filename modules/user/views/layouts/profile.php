<?php

/* @var $this \app\modules\core\components\View */
/* @var $content string */

use app\modules\user\widgets\InfoProfileWidget;
use yii\widgets\Menu;

$menuLink = isset($this->params['actionMenu']) && count($this->params['actionMenu'])
    ?$this->params['actionMenu']
    :[];

$this->beginContent('@app/modules/core/views/layouts/admin.php');
?>
<div class="row">
    <div class="col-md-3">
        <?=InfoProfileWidget::widget()?>
    </div>
    <div class="col-md-9">
        <div class="nav-tabs-custom">
            <?php
            echo Menu::widget([
                'items'=>$menuLink,
                'options'=>[
                    'class'=>'nav nav-tabs'
                ],
                'encodeLabels'=>false,
            ]);
            ?>
            <div class="tab-content">
                <div class="tab-pane active">
                    <?=$content?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endContent(); ?>

