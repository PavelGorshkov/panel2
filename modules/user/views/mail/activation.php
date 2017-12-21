<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    Здравствуйте, <?=$fullName?>
</p>
<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    Ваш аккаунт на сайте "<?=app()->name?>" был успешно создан.
</p>
<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    Для подтверждения электронной почты перейдите, пожалуйста "<?= Html::a('по ссылке', Url::to(['/activation', 'token'=>$token->token], 1))?>"
</p>
<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;">
    P.S. Если вы получили это сообщение по ошибке, просто удалите его!
</p>
