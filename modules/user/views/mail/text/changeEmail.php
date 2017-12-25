<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
Здравствуйте, <?=$fullName?>

На сайте "<?=app()->name?>" Вы успешно изменили e-mail.

Для подтверждения электронной почты перейдите, пожалуйста по ссылке: <?=Url::to(['/user/account/confirm', 'token'=>$token->token], 1)?>

Срок действия ссылки до <?=$expire?>

P.S. Если вы получили это сообщение по ошибке, просто удалите его!