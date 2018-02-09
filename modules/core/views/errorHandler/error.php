<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */
/* @var $code int  */

use yii\helpers\Html;
use yii\helpers\Url;

//$this->title = $name;
?>
<div class="error-page">
    <h2 class="headline <?=$code<500?'text-yellow':'text-red'?>"><?=$code?></h2>
    <div class="error-content">
        <h3><i class="fa fa-warning <?=$code<500?'text-yellow':'text-red'?>"></i> Error!</h3>
        <p>
            <?=nl2br(Html::encode($message));?>
        </p>
        <?=Html::a('Вернуться', Url::previous())?>
    </div><!-- /.error-content -->
</div><!-- /.error-page -->
