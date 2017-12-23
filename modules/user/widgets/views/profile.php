<?php
/* @var string $full_name */
/* @var string $icon */
/* @var string $email */
/* @var string $about */
/* @var string $phone */
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="box-body box-profile">
    <?=Html::img(Url::to($icon), [
        'class'=>'profile-user-img img-responsive img-circle',
        'title'=>$full_name
    ]);?>
    <h3 class="profile-username text-center"><?=$full_name?></h3>
    <p class="text-muted text-center"><?=$email?></p>
    <p class="text-muted text-center"><?=$about?></p>
    <?php if ($phone):?>
    <p class="text-muted text-center"><i class="fa fa-fw fa-phone"></i> <?=Html::a($phone, Url::to('tel:'.$phone))?></p>
    <?php endif; ?>
    <p class="text-muted text-center"><i class="fa fa-fw fa-envelope"></i> <?=Html::a($email, Url::to('mailto:'.$email))?></p>
</div>

