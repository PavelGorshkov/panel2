<?php
/* @var View $this */
/* @var IdentityUser $user */
/* @var Profile $profile */
/* @var Module $module */

use app\modules\core\components\View;
use app\modules\user\models\IdentityUser;
use app\modules\user\models\Profile;
use app\modules\user\Module;
use yii\base\Exception;
use yii\helpers\Html;
use yii\web\HttpException;

?>
<a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <?php try {
        echo Html::img($user->getAvatar(24), ['alt' => $profile->full_name, 'class' => "user-image"]);
    } catch (HttpException $e) {
        echo $e->getMessage();
    } catch (Exception $e) {
        echo $e->getMessage();
    } ?>
    <span class="hidden-xs"><?=$user->email??'Пользователь'?></span>
</a>
<ul class="dropdown-menu">
    <!-- User image -->
    <li class="user-header">
        <?php try {
            echo Html::img($user->getAvatar(160), ['alt' => $profile->full_name, 'class' => "img-circle"]);
        } catch (HttpException $e) {
            echo $e->getMessage();
        } catch (Exception $e) {
            echo $e->getMessage();
        } ?>
        <p>
            <?=$profile->full_name?>
            <small><?=$profile->department?></small>
        </p>
    </li>
    <!-- Menu Body -->
    <? //<li class="user-body"></li>?>
    <!-- Menu Footer-->
    <li class="user-footer">
        <div class="pull-left">
            <?=Html::a('Профиль', ['/user/profile/index'], array('class'=>'btn btn-default btn-flat'))?>
        </div>
        <div class="pull-right">
            <?= Html::a(
                'Выход',
                $module->logoutPage,
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

