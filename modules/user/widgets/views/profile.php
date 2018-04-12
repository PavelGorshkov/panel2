<?php
/* @var IdentityUser $user */
/* @var Profile $profile */
/* @var Module $module */

use app\modules\user\models\IdentityUser;
use app\modules\user\models\Profile;
use app\modules\user\Module;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="box box-solid">
    <div class="box-body box-profile">
        <?php try {
            echo Html::img($user->getAvatar(128), ['alt' => $profile->full_name, 'class' => "profile-user-img img-responsive img-circle"]);
        } catch (Exception $e) {
            echo $e->getMessage();
        } ?>
        <h3 class="profile-username text-center"><?=$profile->full_name?></h3>
        <p class="text-muted text-center"><?=$user->email?></p>
        <p class="text-muted text-center"><?=$profile->department?></p>
        <ul class="list-group list-group-unbordered">
    <?php if ($profile->phone):?>
            <li class="list-group-item" style="background: none;">
                <b><i class="fa fa-fw fa-phone"></i></b> <?=Html::a($profile->phone, Url::to('tel:'.$profile->phone), ['class'=>'pull-right'])?>
            </li>
    <?php endif; ?>
            <li class="list-group-item" style="background: none;">
                <b><i class="fa fa-fw fa-envelope"></i></b> <?=Html::a($user->email, Url::to('mailto:'.$user->email), ['class'=>'pull-right'])?>
            </li>
        </ul>
    </div>
</div>
