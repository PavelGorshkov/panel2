<?php
use app\modules\user\models\Token;
use yii\helpers\Url;

/** @var string $fullName */
/** @var Token $token */
/** @var string $expire */
?>
Здравствуйте, <?=$fullName?>

На сайте "<?=app()->name?>" Вы успешно изменили e-mail.

Для подтверждения электронной почты перейдите, пожалуйста по ссылке: <?=Url::to(['/user/profile/confirm', 'token'=>$token->token], 1)?>

Срок действия ссылки до <?=$expire?>

P.S. Если вы получили это сообщение по ошибке, просто удалите его!