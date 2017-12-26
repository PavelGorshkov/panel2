<?php
use yii\helpers\Url;

/** @var string $fullName */

?>
Здравствуйте, <?=$fullName?>

На сайте "<?=app()->name?>" Вы успешно изменили пароль.

Адрес: <?= Url::to(['/login'], 1)?>


