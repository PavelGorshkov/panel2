<?php
/* @var string $full_name */
/* @var string $icon */
/* @var string $email */
/* @var string $about */
/* @var string $phone */
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="box box-solid">
    <div class="box-body box-profile">
        <?=Html::img(Url::to($icon), [
            'class'=>'profile-user-img img-responsive img-circle',
            'title'=>$full_name
        ]);?>
        <h3 class="profile-username text-center"><?=$full_name?></h3>
        <p class="text-muted text-center"><?=$email?></p>
        <p class="text-muted text-center"><?=$about?></p>
        <ul class="list-group list-group-unbordered">
    <?php if ($phone):?>
            <li class="list-group-item" style="background: none;">
                <b><i class="fa fa-fw fa-phone"></i></b> <?=Html::a($phone, Url::to('tel:'.$phone), ['class'=>'pull-right'])?>
            </li>
    <?php endif; ?>
            <li class="list-group-item" style="background: none;">
                <b><i class="fa fa-fw fa-envelope"></i></b> <?=Html::a($email, Url::to('mailto:'.$email), ['class'=>'pull-right'])?>
            </li>
        </ul>
    </div>
</div>
