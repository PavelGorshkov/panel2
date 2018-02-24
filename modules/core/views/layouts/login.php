<?php

/* @var $this \app\modules\core\components\View */
/* @var $content string */

use app\modules\core\assets\AdminLteAssets;
use yii\helpers\Html;

AdminLteAssets::register($this);
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
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <!--suppress JSUnresolvedLibraryURL -->
    <?=Html::jsFile('@web/js/html5shiv.min.js');?>
    <!--suppress JSUnresolvedLibraryURL -->
    <?=Html::jsFile('@web/js/html5shiv.min.js');?>
    <![endif]-->
</head>
<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>
</html>
<?php $this->endPage();