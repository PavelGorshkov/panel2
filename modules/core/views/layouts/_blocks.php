<?php

use app\modules\core\widgets\AdminMenu;
use app\modules\core\widgets\MenuWidget;
use app\modules\user\widgets\InfoNavMenu;
use yii\widgets\Block;

Block::begin(['id'=>'navbarTopMenu']);
if (!user()->isGuest):?>
    <div class="navbar-custom-menu" style="float: left;">
<?=AdminMenu::widget(['menu'=>'admin']);?>
</div>
<div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu">
            <?=InfoNavMenu::widget()?>
        </li>
        <!-- Control Sidebar Toggle Button -->
        <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
        </li>
    </ul>
</div>
<?php
endif;

Block::end();

Block::begin(['id'=>'controlSidebar']);
?>
<aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
        <li><a href='#control-sidebar-theme-options-tab' data-toggle='tab'><i class='fa fa-paint-brush'></i></a></li>
        <li><a href="#control-sidebar-redactor-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        <li><a href="#control-sidebar-dictionary-tab" data-toggle="tab"><i class="fa fa-book"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <!-- Skins tab content -->
        <div class="tab-pane active" id="control-sidebar-theme-options-tab"><?php include '_tab_skins.php';?></div><!-- /.tab-pane -->
        <!-- Home tab content -->
        <div class="tab-pane" id="control-sidebar-redactor-tab">
            <?=MenuWidget::widget(['menu' => 'redactor', 'view' => 'right_menu']);?>
        </div><!-- /.tab-pane -->

        <?php/* if (app()->moduleManager->isInstallModule('dictionary1')):?>
    <?php if (user()->checkAccess(\dictionary1\auth\TaskDictionary::OPERATION_EDIT)):?>
         <div class="tab-pane" id="control-sidebar-dictionary-tab">
             <?php $this->widget('dictionary1\widgets\menuPanel');?>
         </div><!-- /.tab-pane -->
    <?php endif; ?>
<?php endif; */?>
    </div>
</aside><!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
<?php
Block::end();