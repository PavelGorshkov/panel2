<?php

/* @var $this \app\modules\core\components\View */
/* @var $content string */

use app\modules\core\assets\AdminLteAssets;
use app\modules\user\widgets\FlashMessages;
use yii\helpers\Html;

AdminLteAssets::register($this);
$this->beginPage();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $this->title ? (Html::encode(strip_tags($this->title)) . ' - ') : ''; ?><?= app()->name; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<?php $this->beginBody() ?>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <?= Html::a(app()->name, ['/']) ?>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <?php
        try {
            echo FlashMessages::widget();
        } catch (Exception $e) {
            echo $e->getMessage();
        } ?>
        <?= $content;?>
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
</body>
<?php $this->endBody() ?>
</html>
<?php $this->endPage();