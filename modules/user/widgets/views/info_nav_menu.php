<?php
/* @var View $this */
/* @var string $mini_icon url */
/* @var string $icon url */
/* @var string $email */
/* @var string $full_name */
/* @var string $about */

use app\modules\core\components\View;
use yii\helpers\Html;

?>
<a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <img src="<?=$mini_icon?>" class="user-image" alt="<?=$full_name?>">
    <span class="hidden-xs"><?=$email?$email:'Пользователь'?></span>
</a>
<ul class="dropdown-menu">
    <!-- User image -->
    <li class="user-header">
        <img src="<?=$icon?>" class="img-circle" alt="<?=$full_name?>">
        <p>
            <?=$full_name?>
            <small><?=$about?></small>
        </p>
    </li>
    <!-- Menu Body -->
    <? //<li class="user-body"></li>?>
    <!-- Menu Footer-->
    <li class="user-footer">
        <div class="pull-left">
            <?=Html::a('Профиль', array('/user/profile/index'), array('class'=>'btn btn-default btn-flat'))?>
        </div>
        <div class="pull-right">
            <?= Html::a(
                'Выход',
                ['/logout'],
                [
                    'class'=>'btn btn-default btn-flat',
                    'data'=>[
                        'method'=>'post',
                    ]
                ]
            )?>
        </div>
    </li>
</ul>

