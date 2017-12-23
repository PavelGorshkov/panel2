<?php
/* @var $this \app\modules\core\components\View */
/* @var $module Module */
/* @var $info User */
/* @var $profile UserProfile */


use app\modules\core\widgets\BoxSolidWidget;
use app\modules\user\models\User;
use app\modules\user\models\UserProfile;
use app\modules\user\Module;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="row">
    <div class="col-sm-12">
        <?=Html::a('<i class="fa fa-pencil"></i> Изменить', Url::to(), ['class'=>'btn btn-sm btn-warning'])?>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
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
</table>

        <?php BoxSolidWidget::end()?>
    </div>
</div>
<?php
printr($info);
