<?php
use yii\helpers\Url;

?>
Здравствуйте, <?=$fullName?>

На сайте "<?=app()->name?>" Вы успешно изменили пароль.

Адрес: <?= Url::to(['/login'], 1)?>


