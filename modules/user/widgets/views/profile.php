<?php
/* @var string $full_name */
/* @var string $icon */
/* @var string $email */
/* @var string $about */
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="box box-primary">
    <div class="box-body box-profile">
        <?=Html::img(Url::to($icon), [
            'class'=>'profile-user-img img-responsive img-circle',
            'title'=>$full_name
        ]);?>
        <h3 class="profile-username text-center"><?=$full_name?></h3>
        <p class="text-muted text-center"><?=$email?></p>
        <p class="text-muted text-center"><?=$about?></p>
    </div>
</div>