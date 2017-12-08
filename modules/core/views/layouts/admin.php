<?php

/* @var $this \app\modules\core\components\View */
/* @var $content string */

use app\modules\core\assets\AdminLteAssets;
use app\modules\core\assets\iCheckAssets;
use app\modules\core\widgets\MenuWidget;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

$icon = Html::img(implode('/', [
    app()->getRequest()->getBaseUrl(true),
    app()->getModule('core')->uploadPath,
    app()->getModule('core')->imageUploadPath,
    'logo.png'
]));

AdminLteAssets::register($this);
ICheckAssets::register($this);

include __DIR__ . '/_blocks.php';
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= $this->title ? (Html::encode(strip_tags($this->title)) . ' - ') : ''; ?><?= app()->name; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <?php $this->head() ?>
        <!-- Theme style -->
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script type="text/javascript">
            var panelTokenName = '<?= app()->getRequest()->csrfParam;?>';
            var panelToken = '<?= app()->getRequest()->csrfToken;?>';
        </script>
    </head>
    <body class="
    hold-transition
    sidebar-mini
    skin-green-light
    <? //=(isset(Setting::model()->skinTemplate)?Setting::model()->skinTemplate:'')?>"
        >
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <header class="main-header">
            <a href="<?= app()->homeUrl ?>" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><?= $icon ?></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><?= $icon ?> <?= app()->name ?></span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Top Menu -->
                <?= $this->blocks['navbarTopMenu']; ?>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <?= MenuWidget::widget([
                    'menu' => 'main',
                    'view' => 'left_menu'
                ]); ?>
            </section>
            <!-- /.sidebar -->
        </aside>

        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1><?= $this->getTitle() ?>
                    <small><?= $this->getSmallTitle() ?></small>
                </h1>
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
            </section>

            <!-- Main content -->
            <section class="content">
                <?= Alert::widget() ?>
                <?php // $this->widget('user\widgets\FlashMessages'); ?>
                <?= $content ?>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="pull-right hidden-xs"></div>
            <strong>&copy; <?= app()->getModule('core')->copyright ?>  <?= date('Y') ?> Все права защищены</strong>
        </footer>

        <?= $this->blocks['controlSidebar']; ?>
    </div>
    <!-- ./wrapper -->
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
