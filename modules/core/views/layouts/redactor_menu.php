<?php

/* @var $this \app\modules\core\components\View */
/* @var $content string */

use yii\widgets\Menu;

$menuLink = isset($this->params['actionMenu']) && count($this->params['actionMenu'])
    ?$this->params['actionMenu']
    :[];

$this->beginContent('@app/modules/core/views/layouts/admin.php');
?>
<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <?= /** @noinspection PhpUnhandledExceptionInspection */
            Menu::widget([
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
<?php $this->endContent();