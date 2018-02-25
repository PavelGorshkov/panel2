<?php

/* @var $this \app\modules\core\components\View */
/* @var $content string */

/* @var Module $core */

use app\modules\core\assets\AdminLteAssets;
use app\modules\core\assets\iCheckAssets;
use app\modules\core\Module;
use app\modules\core\widgets\MenuWidget;
use app\modules\user\helpers\UserSettings;
use app\modules\user\widgets\FlashMessages;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$core = app()->getModule('core');
$icon = Html::img(implode('/', [
    app()->getRequest()->getBaseUrl(),
    $core->uploadPath,
    $core->imageUploadPath,
    'logo.png'
]));

AdminLteAssets::register($this);
ICheckAssets::register($this);

include __DIR__ . '/_blocks.php';

$this->beginPage() ?>
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
    </head>
    <body class="hold-transition sidebar-mini
    <?= (isset(UserSettings::model()->skinTemplate) ? UserSettings::model()->skinTemplate : 'skin-green-light') ?>
    <?= (isset(UserSettings::model()->sideBar) ? UserSettings::model()->sideBar : '') ?>"
    >
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <header class="main-header">
            <a href="<?= Url::to(app()->homeUrl) ?>" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><?= $icon ?></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><?= $icon ?> <?= app()->name ?></span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" data-href="/site/sidebar">
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
                    'links' => $this->getBreadcrumbs(),
                    'homeLink' => ['label' => '<i class="fa fa-fw fa-home"></i> Главная', 'url' => '/', 'encode' => false],
                ]) ?>
            </section>

            <!-- Main content -->
            <section class="content">
                <?= FlashMessages::widget() ?>
                <?= $content ?>
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="pull-right hidden-xs"></div>
            <strong>&copy; <?= /** @noinspection PhpUndefinedFieldInspection */
                app()->getModule('core')->copyright ?>  <?= date('Y') ?> Все права защищены</strong>
        </footer>

        <?= $this->blocks['controlSidebar']; ?>
    </div>
    <!-- ./wrapper -->
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
