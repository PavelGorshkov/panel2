<?php
use yii\helpers\Url;

/** @var string $fullName */
/** @var string $email */
/** @var string $password */
?>
Здравствуйте, <?=$fullName?>

На сайте "<?=app()->name?>" Вы восстановили пароль.

Для входа в систему используйте следующие данные:

Адрес: <?= Url::to(['/login'], 1)?>

Логин: <?=$email?>

Пароль: <?=$password?>
