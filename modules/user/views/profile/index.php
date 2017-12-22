<?php
    /* @var $this \app\modules\core\components\View */
    /* @var $admin array */
    /* @var $main array */
    /* @var $redactor array */
    /* @var $dictionary array */

    use app\modules\core\widgets\ShortCutMenuWidget;
?>
    <div class="row">
        <div class="col-sm-12">
            <?=ShortCutMenuWidget::widget(['menu'=>$admin, 'title'=>'Меню администратора'])?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?=ShortCutMenuWidget::widget(['menu'=>$main, 'title'=>'Основное меню'])?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?=ShortCutMenuWidget::widget(['menu'=>$redactor, 'title'=>'Меню редактора'])?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?=ShortCutMenuWidget::widget(['menu'=>$dictionary, 'title'=>'Справочники'])?>
        </div>
    </div>
<?php
