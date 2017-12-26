<?php
use app\modules\user\models\UserToken;
use yii\helpers\Url;

/** @var string $fullName */
/** @var UserToken $token */
/** @var string $expire */
?>
Здравствуйте, <?=$fullName?>

На сайте "<?=app()->name?>" Вы запустили процедуру "Восстановления пароля".

Для восстановления пароля перейдите, пожалуйста, по ссылке "<?= Url::to(['/recovery-password', 'token'=>$token->token], 1)?>"

Срок действия ссылки до <?=$expire?>

P.S. Если вы получили это сообщение по ошибке, просто удалите его!
