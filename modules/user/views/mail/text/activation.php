<?php
use app\modules\user\models\Token;
use yii\helpers\Url;

/** @var string $fullName */
/** @var Token $token */
/** @var string $expire */

?>
Здравствуйте, <?=$fullName?>

Ваш аккаунт на сайте "<?=app()->name?>" был успешно создан.

Для подтверждения электронной почты перейдите, пожалуйста по ссылке "<?= Url::to(['/activation', 'token'=>$token->token], 1)?>"

Срок действия ссылки до <?=$expire?>

P.S. Если вы получили это сообщение по ошибке, просто удалите его!