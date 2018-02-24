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
            <?php try {
                echo ShortCutMenuWidget::widget(['menu' => $admin, 'title' => 'Меню администратора']);
            } catch (Exception $e) {
                $e->getMessage();
            } ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?php
            try {
                echo ShortCutMenuWidget::widget(['menu' => $main, 'title' => 'Основное меню']);
            } catch (Exception $e) {
                $e->getMessage();
            } ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?php try {
                echo ShortCutMenuWidget::widget(['menu' => $redactor, 'title' => 'Меню редактора']);
            } catch (Exception $e) {
                $e->getMessage();
            } ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?php try {
                echo ShortCutMenuWidget::widget(['menu' => $dictionary, 'title' => 'Справочники']);
            } catch (Exception $e) {
                $e->getMessage();
            } ?>
        </div>
    </div>
<?php
