<?php
/* @var $this \app\modules\core\components\View */
/* @var $module Module */
/* @var $info User */
/* @var $profile Profile */

use app\modules\core\widgets\BoxSolidWidget;
use app\modules\user\models\User;
use app\modules\user\models\Profile;
use app\modules\user\Module;
use app\modules\user\widgets\AvatarWidget;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="row">
    <div class="col-sm-12">
        <?=Html::a('<i class="fa fa-pencil"></i> Изменить', Url::to(['update']), ['class'=>'btn btn-sm btn-warning'])?>
    <?php if ($module->isNotFromLDAP()):?>
        <?=Html::a('<i class="fa fa-lock"></i> Сменить пароль', Url::to(['change-password']), ['class'=>'btn btn-sm btn-info'])?>
    <?php endif;?>
    </div>
</div>
<div class="row">
    <div class="col-sm-5">
        <?php BoxSolidWidget::begin([
            'title'=>'Аккаунт',
            'boxBodyClass'=>'no-padding',
        ])?>
<table class="table table-condensed table-hover">
<?php if (!$module->generateUserName):?>
    <tr>
        <th>Логин</th>
        <td><?=$info->username?></td>
    </tr>
<?php endif;?>
    <tr>
        <th>Email</th>
        <td><?=$info->email?></td>
    </tr>
    <tr>
        <th>Группа</th>
        <td><?=$info->getAccessGroup()?></td>
    </tr>
</table>
        <?php BoxSolidWidget::end()?>
    </div>
    <div class="col-sm-7">
        <?php BoxSolidWidget::begin([
            'title'=>'Основная информация',
            'boxBodyClass'=>'no-padding',
        ])?>
        <table class="table table-condensed table-hover">
            <tr>
                <th>ФИО</th>
                <td><?=$profile->full_name?></td>
            </tr>
            <tr>
                <th>Телефон</th>
                <td><?=$profile->phone?></td>
            </tr>
            <tr>
                <th>Должность, место работы</th>
                <td><?=$profile->about?></td>
            </tr>
            <tr>
                <th>Аватар</th>
                <td><?=AvatarWidget::widget()?></td>
            </tr>
        </table>
        <?php BoxSolidWidget::end()?>
    </div>
</div>
<?php

